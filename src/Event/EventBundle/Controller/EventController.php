<?php

namespace Event\EventBundle\Controller;

class EventController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Event:index.html.twig', []);
    }

    public function carouselAction()
    {
        return $this->render('EventEventBundle:Component:_carousel.html.twig', [
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent()
        ]);
    }
}
