<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Sponsor;
use Event\EventBundle\Entity\Translation\SponsorTranslation;
use Event\EventBundle\Form\Type\SponsorType;

class SponsorController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Sponsor:index.html.twig', array(
            'sponsors' => $this->getRepository('EventEventBundle:Sponsor')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Sponsor();
            $entity = $this->initObjectLocales($entity, new SponsorTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Sponsor', $id);
        }

        $form = $this->createForm(SponsorType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $successFlashText = sprintf('Sponsor %s updated.', $entity->getCompany());
                if (!$id) {
                    $successFlashText = sprintf('Sponsor %s added.', $entity->getCompany());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_sponsor');
            }
        }

        return $this->render('EventEventBundle:Backend/Sponsor:manage.html.twig', [
            'sponsor' => $entity,
            'form' => $form->createView(),
            'configLocales' => $this->container->getParameter('event.locales')
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Sponsor', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Sponsor deleted.');

        return $this->redirectToRoute('backend_sponsor');
    }
}
