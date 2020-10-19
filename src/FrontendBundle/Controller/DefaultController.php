<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        $admin=false;
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
                return $this->render('@Frontend/Default/index.html.twig');
            }
        }
        return $this->render('@Frontend/Default/index.html.twig');
    }


    public function aboutAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('frontend/about.html.twig', [
        ]);
    }

    public function contactAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('frontend/contact.html.twig', [
        ]);
    }
}
