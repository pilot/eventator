<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Program;
use Event\EventBundle\Entity\Translation\ProgramTranslation;
use Event\EventBundle\Form\Type\ProgramType;

class ProgramController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Program:index.html.twig', array(
            'program' => $this->getRepository('EventEventBundle:Program')->findBy([], ['startDate' => 'ASC'])
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Program();
            $entity = $this->initObjectLocales($entity, new ProgramTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Program', $id);
        }

        $form = $this->createForm(ProgramType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $this->setSuccessFlash('Program updated.');

                return $this->redirectToRoute('backend_program');
            }
        }

        return $this->render('EventEventBundle:Backend/Program:manage.html.twig', [
            'program' => $entity,
            'form' => $form->createView()
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Program', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Program deleted.');

        return $this->redirectToRoute('backend_program');
    }
}
