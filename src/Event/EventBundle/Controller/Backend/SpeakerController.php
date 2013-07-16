<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Speaker;
use Event\EventBundle\Entity\SpeakerTranslation;
use Event\EventBundle\Form\Type\SpeakerType;

class SpeakerController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Speaker:index.html.twig', array(
            'speakers' => $this->getRepository('EventEventBundle:Speaker')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Speaker();
            $entity = $this->initObjectLocales($entity, new SpeakerTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Speaker', $id);
        }

        $form = $this->createForm(new SpeakerType(), $entity);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $this->setSuccessFlash(sprintf('Speaker %s updated.', $entity->getFullName()));

                return $this->redirectToRoute('backend_speaker');
            }
        }

        return $this->render('EventEventBundle:Backend/Speaker:manage.html.twig', [
            'speaker' => $entity,
            'form' => $form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Speaker', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Speaker deleted.');

        return $this->redirectToRoute('backend_speaker');
    }


}
