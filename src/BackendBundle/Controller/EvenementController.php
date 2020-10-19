<?php

namespace BackendBundle\Controller;

use AppBundle\Entity\Evenement;
use BackendBundle\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends Controller
{
    public function afficheAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        $dql   = "SELECT a FROM AppBundle:Evenement a ";
        $query = $em->createQuery($dql);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@Backend/Evenement/afficheevenement.html.twig', ['pagination' => $pagination]);

    }
    public function createAction(Request $request)
    {
        $evenement=new Evenement();

        $form=$this->createForm(EvenementType::class,$evenement);

        $form=$form->handleRequest($request);

        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $image = $form->get('imageFile')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('evenemnts_image_directory'),
                        $newFilename
                    );
                    $evenement->setImagepath($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('evenement_affiche');
        }
        return $this->render('@Backend/Evenement/ajouterevenement.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    public function updateAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);

        $form=$this->createForm(EvenementType::class,$evenement);
        $form=$form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $image = $form->get('imageFile')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('evenemnts_image_directory'),
                        $newFilename
                    );
                    $evenement->setImage($newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


            }


            $em->persist($evenement);

            $em->flush();
            return $this->redirectToRoute('evenement_affiche');
        }

        return $this->render('@Backend/Evenement/modifierevenement.html.twig', array(
            'form'=>$form->createView(),
            'image' =>$evenement->getImagePath(),
            'evenement'=>$evenement->getLocalisation()

        ));
    }
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository(Evenement::class)->find($id);
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute("evenement_affiche");
    }





    public function affichePublicationAction(Request $request){
        $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        // $dql   = "SELECT a FROM AppBundle:Participation p where".$event."=p.eventid";
        $dql   = "SELECT r
                FROM AppBundle:Publication r ";//join AppBundle:Evenement e on e.Id = p.evenement where p.evenement=1
        // $query = $em->createQuery($dql);
        $query = $em->createQuery($dql
        );
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        // parameters to template
        return $this->render('@Backend/Publication/malistdePublication.html.twig', ['pagination' => $pagination]);
    }

    public function deletePublicationAdminAction($id){
        $em = $this->getDoctrine()->getManager();
        $publication=  $em->getRepository('AppBundle:Publication')->find($id);
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('publication_affiche_admin');


    }
    public function validerPublicationAdminAction($id){
        $em = $this->getDoctrine()->getManager();
        $publication=  $em->getRepository('AppBundle:Publication')->find($id);
        $publication->setIsValid(1);
        $em->persist($publication);
        $em->flush();
        return $this->redirectToRoute('publication_affiche_admin');
    }

}
