<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Program;
use Event\EventBundle\Entity\Translation\ProgramTranslation;
use Event\EventBundle\Form\Type\ProgramType;

class ProgramController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Program:index.html.twig', array(
            'events' => $this->getRepository('EventEventBundle:Event')->findBy([], ['id' => 'DESC']),
        ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxProgramListAction(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $searchQuery = $request->get('search')['value'];
        $order = $request->get('order');
        $eventId = $request->get('event', $this->getEvent()->getId());

        $program = $this->getRepository('EventEventBundle:Program')->getProgramByParameters($eventId, $searchQuery, $order, $start, $length);
        $programCount = $this->getRepository('EventEventBundle:Program')->getProgramCountByParameters($eventId, $searchQuery);

        $data = array_map(function (Program $entity) {
            $actions = [
                'editUrl' => $this->generateUrl('backend_program_edit', ['id' => $entity->getId()]),
                'deleteUrl' => $this->generateUrl('backend_program_delete', ['id' => $entity->getId()]),
            ];

            return [
                $entity->getId(),
                '<a href="'.$this->generateUrl('backend_program_edit', ['id' => $entity->getId()]).'">'.
                    ($entity->getTitle() ? $entity->getTitle() : $entity->getSpeech()->getTitle()).
                '</a>',
                $entity->getStartDate()->format('F d, Y H:i').' - '.$entity->getEndDate()->format('H:i'),
                $actions,

            ];
        }, $program);

        return new JsonResponse([
            'draw'            => $request->get('draw') ? intval($request->get('draw')) : 0,
            "recordsTotal"    => $programCount,
            "recordsFiltered" => $programCount,
            "data"            => $data,
        ]);
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

                $successFlashText = sprintf('Program updated.');
                if (!$id) {
                    $successFlashText = sprintf('Program added.');
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_program');
            }
        }

        return $this->render('EventEventBundle:Backend/Program:manage.html.twig', [
            'program' => $entity,
            'form' => $form->createView(),
            'configLocales' => $this->container->getParameter('event.locales')
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
