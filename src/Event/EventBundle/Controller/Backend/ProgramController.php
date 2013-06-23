<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class ProgramController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend:index.html.twig', 
            array()
        );
    }
}
