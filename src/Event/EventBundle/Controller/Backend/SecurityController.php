<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        return $this->render('EventEventBundle:Backend/Security:login.html.twig', array(
            'error' => false   
        ));
    }
}
