<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Controller\Controller;

class SoldWorkshopController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/SoldWorkshop:index.html.twig', array(
            'sold_workshops' => $this->getRepository('EventEventBundle:SoldWorkshop')->findAll()
        ));
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();
        $translator = $this->get('translator');

        $entity = $this->findOr404('EventEventBundle:SoldWorkshop', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash($translator->trans('Workshop Ticket deleted.'));

        return $this->redirectToRoute('backend_sold_workshop');
    }
}
