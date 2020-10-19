<?php

namespace FrontendBundle\Controller;

use AppBundle\Entity\Evenement;
use AppBundle\Entity\Participation;
use AppBundle\Entity\Publication;
use AppBundle\Form\ParticipationType;
use FrontendBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

class ParticipationController extends Controller
{
    public function createAction($eventId, Request $request )
    {
        $participation =new Participation();


            $participation->setUser($this->getUser());
            $event =$this->getDoctrine()->getRepository(Evenement::class)->find($eventId);
            $user=$this->getUser();
       // $user2=$this->getDoctrine()->getManager()->getRepository(\UserBundle\Entity\User::class)->findBy(['userName'  => $user->get]);
            $verification=$this->getDoctrine()->getRepository(Participation::class)->findBy([
                'User' => $user,
                'evenement' => $event
            ]);

            if($verification==null){
                $participation->setEvenement($event);
                $participation->setNom($user->getUsername());

                $char ="abcdefghijklmnopqrstvwxyz0123456789";
                $chaineAleatoire =str_shuffle($char);
                $code= substr($chaineAleatoire,1,10);
                $participation->setCode($code);

                /*$participation->setEmail($user->getUsername());
                $participation->setCin(32165489);
                $participation->setPrenom("lksvnlsdkvn");*/
                $participation->setUser($user);
              //  $participation->setNumtel(654654);

                $em=$this->getDoctrine()->getManager();
                $em->persist($participation);
                $em->flush();
               // return $this->redirectToRoute('participation_list',array('eventId' => $eventId,'user' =>$user));
           // return $this->redirectToRoute('publication_add',array('eventId' => $eventId));
            //}

        return $this->redirectToRoute('participation_list');
       // return $this->redirectToRoute('publication_add',array('eventId' => $eventId));

        }


        return $this->render('@Frontend/Evenement/showoneEvenement.html.twig', array(
            'evenement'=>$event,'error'=>'Vous etes déja participé à cet évenement'
        ));
    }

    public function afficheParticipationByEventUserAction(Request $request){


        $em    = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

       // $dql   = "SELECT a FROM AppBundle:Participation p where".$event."=p.eventid";
        $dql   = "SELECT a,r
                FROM AppBundle:Participation r JOIN r.evenement a
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
        return $this->render('@Frontend/Participation/listParticipation.html.twig', ['pagination' => $pagination]);

    }
    public function afficheParticipationcardAction($id){

        $participation=$this->getDoctrine()->getRepository(Participation::class)->find($id);

        return $this->render('@Frontend/Participation/participationCard.html.twig', ['par' => $participation]);
    }

    public function participationdeleteAction($id){
        $participation=$this->getDoctrine()->getRepository(Participation::class)->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($participation);
        $em->flush();
        return $this->redirectToRoute('participation_list');
    }
}
