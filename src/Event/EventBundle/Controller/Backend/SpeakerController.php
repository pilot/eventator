<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class SpeakerController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Speaker:index.html.twig', array(
            'speakers' => $this->getRepository('EventEventBundle:Speaker')->findAll()
        ));
    }
}
