<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Organizer;
use Event\EventBundle\Entity\Translation\OrganizerTranslation;
use Event\EventBundle\Form\Type\OrganizerType;

class OrganizerController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Organizer:index.html.twig', array(
            'organizers' => $this->getRepository('EventEventBundle:Organizer')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Organizer();
            $entity = $this->initObjectLocales($entity, new OrganizerTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Organizer', $id);
        }

        $form = $this->createForm(OrganizerType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $successFlashText = sprintf('Organizer %s updated.', $entity->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Organizer %s added.', $entity->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_organizer');
            }
        }

        return $this->render('EventEventBundle:Backend/Organizer:manage.html.twig', [
            'organizer' => $entity,
            'form' => $form->createView(),
            'configLocales' => $this->container->getParameter('event.locales')
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Organizer', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Organizer deleted.');

        return $this->redirectToRoute('backend_organizer');
    }
}
