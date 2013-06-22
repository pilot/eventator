<?php

namespace Event\EventBundle\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EventEventBundle:Default:index.html.twig', array('name' => $name));
    }
}
