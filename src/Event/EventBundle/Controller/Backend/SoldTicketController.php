<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class SoldTicketController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/SoldTicket:index.html.twig', array(
            'sold_tickets' => $this->getRepository('EventEventBundle:soldTicket')->findAll()
        ));
    }
}
