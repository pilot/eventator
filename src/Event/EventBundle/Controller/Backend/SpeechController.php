<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\Speech;
use Event\EventBundle\Entity\Translation\SpeechTranslation;
use Event\EventBundle\Form\Type\SpeechType;

/**
 * Class SpeechController
 */
class SpeechController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/Speech:index.html.twig', array(
            'events' => $this->getRepository('EventEventBundle:Event')->findBy([], ['id' => 'DESC']),
        ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxSpeechesListAction(Request $request)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $searchQuery = $request->get('search')['value'];
        $order = $request->get('order');
        $eventId = $request->get('event');
        if ($eventId) {
            $event = $this->getRepository('EventEventBundle:Event')->find($eventId);
        } else {
            $event = $this->getEvent();
        }


        $speeches = $this->getRepository('EventEventBundle:Speech')->getSpeechesByParameters($event, $searchQuery, $order, $start, $length);
        $speechesCount = $this->getRepository('EventEventBundle:Speech')->getSpeechesCountByParameters($event, $searchQuery);

        $data = array_map(function (Speech $speech) {
            $actions = [
                'editUrl' => $this->generateUrl('backend_speech_edit', ['id' => $speech->getId()]),
                'deleteUrl' => $this->generateUrl('backend_speech_delete', ['id' => $speech->getId()]),
            ];

            return [
                $speech->getId(),
                $speech->getSpeaker()->getFullName(),
                $speech->getLanguage()."&nbsp;/&nbsp;".$speech->getTitle(),
                $actions,

            ];
        }, $speeches);

        return new JsonResponse([
            'draw'            => $request->get('draw') ? intval($request->get('draw')) : 0,
            'recordsTotal'    => $speechesCount,
            'recordsFiltered' => $speechesCount,
            'data'            => $data,
        ]);
    }

    public function manageAction(Request $request, $id = null)
    {
        if ($id === null) {
            $entity = new Speech();
            $entity = $this->initObjectLocales($entity, new SpeechTranslation());
        } else {
            $entity = $this->findOr404('EventEventBundle:Speech', $id);
        }

        $form = $this->createForm(SpeechType::class, $entity);

        if ($request->getMethod() === 'POST') {
            $form->submit($request);

            if ($form->isValid()) {
                $this->getManager()->persist($entity);
                $this->getManager()->flush();

                $successFlashText = sprintf('Speech %s updated.', $entity->getTitle());
                if (!$id) {
                    $successFlashText = sprintf('Speech %s added.', $entity->getTitle());
                }
                $this->setSuccessFlash($successFlashText);

                return $this->redirectToRoute('backend_speech');
            }
        }

        return $this->render('EventEventBundle:Backend/Speech:manage.html.twig', [
            'speech' => $entity,
            'form' => $form->createView(),
            'configLocales' => $this->container->getParameter('event.locales')
        ]);
    }

    public function deleteAction($id)
    {
        $this->isGrantedAdmin();

        $entity = $this->findOr404('EventEventBundle:Speech', $id);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $this->setSuccessFlash('Speech deleted.');

        return $this->redirectToRoute('backend_speech');
    }
}
