<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Event;
use Event\EventBundle\Entity\Translation\EventTranslation;
use Event\EventBundle\Form\Type\EventType;

class EventController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Event:index.html.twig', array(
            'events' => $this->getRepository('EventEventBundle:Event')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Event();
            $entity = $this->initObjectLocales($entity, new EventTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Event', $id);
        }

        $form = $this->createForm(EventType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $successFlashText = sprintf('Event %s updated.', $entity->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Event %s added.', $entity->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_event');
            }
        }

        return $this->render('EventEventBundle:Backend/Event:manage.html.twig', [
            'event' => $entity,
            'form' => $form->createView(),
            'configLocales' => $this->container->getParameter('event.locales')
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Event', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Event deleted.');

        return $this->redirectToRoute('backend_event');
    }


}
