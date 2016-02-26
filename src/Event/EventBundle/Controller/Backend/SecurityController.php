<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('EventEventBundle:Backend/Security:login.html.twig', [
            // last username entered by the user
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }
}
