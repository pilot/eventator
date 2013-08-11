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

    public function speakersAction()
    {
        return $this->render('EventEventBundle:Component:_speakers.html.twig', [
            'speakers' => $this->getRepository('EventEventBundle:Speaker')->findAll()
        ]);
    }

    public function venueAction()
    {
        return $this->render('EventEventBundle:Component:_venue.html.twig', [
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent()
        ]);
    }

    public function scheduleAction()
    {
        return $this->render('EventEventBundle:Component:_schedule.html.twig', [
            'schedule' => $this->getRepository('EventEventBundle:Program')->findBy([], ['startDate' => 'ASC'])
        ]);
    }

    public function sponsorsAction()
    {
        return $this->render('EventEventBundle:Component:_sponsors.html.twig', [
            'sponsors' => $this->getRepository('EventEventBundle:Sponsor')->findAll()
        ]);
    }

    public function contactAction()
    {
        return $this->render('EventEventBundle:Component:_contact.html.twig', [
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent()
        ]);
    }
}
