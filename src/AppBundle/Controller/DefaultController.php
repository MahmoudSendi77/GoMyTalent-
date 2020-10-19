<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    public function indexAction()
    {

       /* $admin=false;
        if($this->getUser())
        {
            foreach($this->getUser()->getRoles() as $role)
            {
                if($role == 'ADMIN')
                {
                    $admin=true;
                }
            }
            if($admin)
            {
                return $this->redirectToRoute('publication_affiche_admin');
            }else
            {
                return $this->redirectToRoute('showenementcalender');
            }
        }
        return $this->render('@Frontend/Default/index.html.twig');
        //  $membre=$this->container->get('security.token_storage')->getToken()->getUser();

        //  if( $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
*/


    }



}
