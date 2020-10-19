<?php

namespace FrontendBundle\Controller;

use AppBundle\Entity\Evenement;
use AppBundle\Entity\Publication;
use FrontendBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends Controller
{


    public function afficheByeventAction($idEvent,equest $request)
    {
        $evenement =$this->getDoctrine()->getRepository(Evenement::class)->find($idEvent);

        $publications=$this->getDoctrine()->getRepository(Publication::class)->findBy([
            'evenement' => $evenement
        ]);
        return $this->render('@Frontend/Publication/affichepublication.html.twig', array(
            'publications'=>$publications
        ));
    }


    public function afficheAction()
    {
        $publications=$this->getDoctrine()->getRepository(Publication::class)->findBy([
            'isValid' => 1
        ]);

        if(!is_null($this->getUser()) )
        if($this->getUser()->hasRole('ADMIN')==1){
            return $this->render('@Backend/Evenement/afficheevenement.html.twig');
        }
        else{

        return $this->render('@Frontend/Publication/affichepublication.html.twig', array(
            'publications'=>$publications
        ));}
        return $this->render('@Frontend/Publication/affichepublication.html.twig', array(
            'publications'=>$publications
        ));
   }

    public function createAction($eventId, Request $request)
    {
        $publication=new publication();
       $form=$this->createForm(PublicationType::class,$publication);
        $form=$form->handleRequest($request);
       $event =$this->getDoctrine()->getRepository(Evenement::class)->find($eventId);
        $publications = $this->getDoctrine()->getRepository(Publication::class)->findBy([
            'User' => $this->getUser(),
            'evenement' => $event
        ]);
        if (count($publications)>=3){
            return $this->render('@Frontend/Evenement/showoneEvenement.html.twig', array(
                'error' => 'désolé vous avez dépassé votre quota des éléments partagés'
            ));
        }

        if($form->isValid()){
            // ge user publications


            // Move the file to the directory wh    ere brochures are stored

                $image = $form->get('fileUpload')->getData();
                if ($image) {
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

                    $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
                    // Move the file to the tory where brochures are stored
                    try {
                        $image->move(
                            $this->getParameter('evenemnts_image_directory'),
                            $newFilename
                        );
                      $publication->setFile($newFilename);
                    } catch (FileException $e){                           // ... handle exception if something happens during fi le upload
                    }

            }

            $publication->setUser($this->getUser());

            $publication->setEvenement($event);
            $em=$this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('publication_affiche');
        }
        return $this->render('@Frontend/Publication/ajoutpublication.html.twig', array(
            'form'=>$form->createView()
        ));


    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $publication=  $em->getRepository('AppBundle:Publication')->findEntitiesByString($requestString);
        if(!$publication) {
            $r = "publication non trouvé :( ";
        } else {
            $html = $this ->renderView('@Frontend/Publication/search.html.twig', array(
                'publications'=> $publication

            ));
            $result=  $html ;
        }
        header_remove();
        return new Response(($result));
    }

   public function afficheOnePublicationAction($id){
        $em = $this->getDoctrine()->getManager();
        $publication=  $em->getRepository('AppBundle:Publication')->find($id);
        return $this->render('@Frontend/Publication/showonePublication.html.twig', array(
            'publication'=>$publication
       ));
    }

    public function afficheMesPublicationAction(Request $request){
         $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        // $dql   = "SELECT a FROM AppBundle:Participation p where".$event."=p.eventid";
        $dql   = "SELECT r
                FROM AppBundle:Publication r
                where r.User= ".$this->getUser()->getId();//join AppBundle:Evenement e on e.Id = p.evenement where p.evenement=1
        // $query = $em->createQuery($dql);
        $query = $em->createQuery($dql
        );
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@Frontend/Publication/malistdePublication.html.twig', ['pagination' => $pagination]);
    }

    public function deletePublicationAction($id){
        $em = $this->getDoctrine()->getManager();
        $publication=  $em->getRepository('AppBundle:Publication')->find($id);
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('showone_mes_publication');


    }

    public function modifierMaPublicationAction($id,Request $request){
        $publication=new publication();

        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($id);
        $form=$this->createForm(PublicationType::class,$publication);
        $form=$form->handleRequest($request);

        if($form->isValid()){
            // ge user publications


            // Move the file to the directory wh    ere brochures are stored

            $image = $form->get('fileUpload')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the tory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('evenemnts_image_directory'),
                        $newFilename
                    );
                    $publication->setFile($newFilename);
                } catch (FileException $e){                           // ... handle exception if something happens during fi le upload
                }

            }

            $em=$this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('showone_mes_publication');
        }
        return $this->render('@Frontend/Publication/modifierpublication.html.twig', array(
            'form'=>$form->createView()
        ));
    }
}