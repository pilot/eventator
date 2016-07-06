<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Entity\CallForPaper;

/**
 * Class CallForPaperController
 */
class CallForPaperController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('EventEventBundle:Backend/CallForPaper:index.html.twig', [
            'events' => $this->getRepository('EventEventBundle:Event')->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxCallsListAction(Request $request)
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


        $calls = $this->getRepository('EventEventBundle:CallForPaper')->getCallsByParameters($event, $searchQuery, $order, $start, $length);
        $callsCount = $this->getRepository('EventEventBundle:CallForPaper')->getCallsCountByParameters($event, $searchQuery);
        $languages = $this->container->getParameter('event.speech_languages');
        $levels = $this->container->getParameter('event.speech_levels');

        $data = array_map(function (CallForPaper $call) use ($languages, $levels) {
            $actions = [
                'deleteUrl' => $this->generateUrl('backend_call_for_paper_delete', ['id' => $call->getId()]),
            ];

            return [
                $call->getId(),
                $call->getTitle(),
                $languages[$call->getLanguage()],
                $levels[$call->getLevel()],
                [
                    'id' => $call->getStatus(),
                    'name' => CallForPaper::$statusNames[$call->getStatus()],
                ],
                $call->getCreated()->format('F d, Y H:i'),
                $actions,

            ];
        }, $calls);

        return new JsonResponse([
            'draw'            => $request->get('draw') ? intval($request->get('draw')) : 0,
            "recordsTotal"    => $callsCount,
            "recordsFiltered" => $callsCount,
            "data"            => $data,
        ]);
    }

    /**
     * @param CallForPaper $callForPaper
     * @param integer      $status
     *
     * @return JsonResponse
     */
    public function changeStatusAction(CallForPaper $callForPaper, $status)
    {
        $callForPaper->setStatus($status);
        $this->getManager()->persist($callForPaper);
        $this->getManager()->flush();

        return new JsonResponse(true);
    }

    /**
     * @param CallForPaper $callForPaper
     *
     * @return RedirectResponse
     */
    public function deleteAction(CallForPaper $callForPaper)
    {
        $this->isGrantedAdmin();

        $this->getManager()->remove($callForPaper);
        $this->getManager()->flush();

        $this->setSuccessFlash('Call deleted.');

        return $this->redirectToRoute('backend_call_for_paper');
    }
}
