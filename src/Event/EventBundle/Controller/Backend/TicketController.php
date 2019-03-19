<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Ticket;
use Event\EventBundle\Form\Type\TicketType;

class TicketController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Ticket:index.html.twig', array(
            'tickets' => $this->getRepository('EventEventBundle:Ticket')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $ticket = new Ticket();            
        } else {
            $ticket = $this->findOr404('EventEventBundle:Ticket', $id);
        }

        $form = $this->createForm(TicketType::class, $ticket);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($ticket);
                $this->getManager()->flush();

                $successFlashText = sprintf('Ticket %s updated.', $ticket->getName());
                if (!$id) {
                    $successFlashText = sprintf('Ticket %s added.', $ticket->getName());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_ticket');
            }
        }

        return $this->render('EventEventBundle:Backend/Ticket:manage.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();
        $translator = $this->get('translator');

        $entity = $this->findOr404('EventEventBundle:Ticket', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash($translator->trans('Ticket deleted.'));

        return $this->redirectToRoute('backend_ticket');
    }


}
