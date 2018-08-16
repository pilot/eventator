<?php

namespace Event\EventBundle\Controller\Backend;

use Event\EventBundle\Entity\WshSchedule;
use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Workshop;
use Event\EventBundle\Form\Type\WorkshopType;
use Event\EventBundle\Form\Type\WshScheduleType;

class WorkshopController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Workshop:index.html.twig', array(
            'workshops' => $this->getRepository('EventEventBundle:Workshop')->findAll()
        ));
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $workshop = new Workshop();
            $workshop->setDateCreated(new \DateTime());
        } else {
            $workshop = $this->findOr404('EventEventBundle:Workshop', $id);
        }
        $workshop->setDateUpdated(new \DateTime());
        $form = $this->createForm(WorkshopType::class, $workshop);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($workshop);
                $this->getManager()->flush();

                $successFlashText = sprintf('Workshop updated.');
                if (!$id) {
                    $successFlashText = sprintf('Workshop added.');
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_workshop');
            }
        }

        return $this->render('EventEventBundle:Backend/Workshop:manage.html.twig', [
            'workshop' => $workshop,
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();
        $translator = $this->get('translator');

        $entity = $this->findOr404('EventEventBundle:Workshop', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash($translator->trans('Workshop Ticket deleted.'));

        return $this->redirectToRoute('backend_workshop');
    }

    public function manageScheduleAction(Request $request, $w_id, $id = null)
    {
        if ($id === null) {
            $schedule = new WshSchedule();
            $workshop = $this->findOr404('EventEventBundle:Workshop', $w_id);
            $schedule->setWorkshop($workshop);
        } else {
            $schedule = $this->findOr404('EventEventBundle:WshSchedule', $id);
        }

        $form = $this->createForm(WshScheduleType::class, $schedule);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($schedule);
                $this->getManager()->flush();

                $successFlashText = sprintf('Schedule item updated.');
                if (!$id) {
                    $successFlashText = sprintf('Schedule item added.');
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_workshop_edit', ['id' => $schedule->getWorkshop()->getId()]);
            }
        }

        return $this->render('EventEventBundle:Backend/Workshop:manageSchedule.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView(),
        ]);
    }

    public function deleteScheduleAction($id)
    {
        $this->isGrantedAdmin();
        $translator = $this->get('translator');

        $entity = $this->findOr404('EventEventBundle:WshSchedule', $id);
        $w_id = $entity->getWorkshop()->getId();
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash($translator->trans('Schedule Item deleted.'));

        return $this->redirectToRoute('backend_workshop_edit', ['id' => $w_id]);
    }


}
