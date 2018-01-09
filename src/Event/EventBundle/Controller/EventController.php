<?php

namespace Event\EventBundle\Controller;

use Behat\Mink\Exception\ResponseTextException;
use Event\EventBundle\Entity\SoldTicket;
use Event\EventBundle\Form\Type\SoldTicketType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Event\EventBundle\Entity\CallForPaper;
use Event\EventBundle\Form\Type\ContactType;
use Event\EventBundle\Form\Type\CallForPaperType;

class EventController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Event:index.html.twig', []);
    }

    public function carouselAction()
    {
        return $this->render('EventEventBundle:Component:_carousel.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function speakersAction()
    {
        $form = $this->callForPaper();

        return $this->render('EventEventBundle:Component:_speakers.html.twig', [
            'currentEvent' => $this->getEvent(),
            'speakers' => $this->getEvent()->getSpeakers(),
            'form' => $form->createView(),
            'captcha' => $this->getCaptcha('captchaResultCall')
        ]);
    }

    public function aboutSymfonyAction()
    {
        return $this->render('EventEventBundle:Component:_about.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function venueAction()
    {
        return $this->render('EventEventBundle:Component:_venue.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function conferencesAction()
    {
        return $this->render('EventEventBundle:Component:conferences.html.twig', []);
    }

    public function scheduleAction(Request $request)
    {
        $host = $request->getHttpHost();

        return $this->render('EventEventBundle:Component:_schedule.html.twig', [
            'schedule' => $this->getRepository('EventEventBundle:Event')->getProgram($host)
        ]);
    }

    public function sponsorsAction()
    {
        return $this->render('EventEventBundle:Component:_sponsors.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function organizersAction()
    {
        return $this->render('EventEventBundle:Component:_organizers.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function ticketsAction()
    {
        return $this->render('EventEventBundle:Component:_tickets.html.twig', [
            'event' => $this->getEvent()
        ]);
    }

    public function buyTicketAction (Request $request){
        $ticket = $this->findOr404('EventEventBundle:Ticket', $request->request->get('ticket_id'));
        $total = $ticket->getPrice();
        if($lunch = $request->request->get('lunch')){
            $total += $ticket->getLunchPrice();
        }
        if($ap = $request->request->get('after-party')){
            $total += $ticket->getApPrice();
        }
        $uid = time();

        if ($request->isMethod('POST') && $sold_tickets = $request->request->get('soldTickets')) {
            foreach ($sold_tickets as $sold_ticket) {
                $entity = new SoldTicket();
                $entity->setTicket($ticket);
                $entity->setFirstName($sold_ticket['firstName']);
                $entity->setLastName($sold_ticket['lastName']);
                $entity->setEmail($request->request->get('email'));
                $entity->setStatus(SoldTicket::STATUS_RESERVED);
                $entity->setUid($uid);
                $this->getManager()->persist($entity);
                $this->getManager()->flush();
            }
            $public_key = $this->container->getParameter('liqpay.publickey');
            $private_key = $this->container->getParameter('liqpay.privatekey');
            $liqpay = new \LiqPay($public_key, $private_key);
            $amount = $total * count($sold_tickets);
            $liqpayData = array(
                'action'         => 'pay',
                'amount'         => $total * count($sold_tickets),
                'currency'       => $ticket->getCurrencylabel(),
                'description'    => 'buying ticket(s)',
                'order_id'       => $uid,
                'version'        => '3',
                'server_url'     => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $this->generateUrl('tickets_handle_liqpay'),
                'result_url'     => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $this->generateUrl('tickets_payment_success'),
            );
            if($this->container->getParameter('liqpay.sandbox') == 1){
                $liqpayData['sandbox'] = '1';
            }
            $html = $liqpay->cnb_form($liqpayData);
            return $this->render('EventEventBundle:Event:liqPay.html.twig', [
                'event'  => $this->getEvent(),
                'hosts'  => $this->getHostYear(),
                'button' => $html,
                'amount' => $amount . ' ' . $ticket->getCurrencyLabel(),
                'count' => count($sold_tickets),
            ]);
        }
        return $this->render('EventEventBundle:Event:buyTicket.html.twig', [
            'event'  => $this->getEvent(),
            'ticket' => $ticket,
            'lunch'  => $lunch,
            'ap'     => $ap,
            'hosts'  => $this->getHostYear(),
            'uid'    => $uid,
            'total'  => $total,
        ]);

    }

    public function handleLiqPayRequestAction(Request $request){
        $data = $request->request->get('data');
        $signature = $request->request->get('signature');
        $privateKey = $this->container->getParameter('liqpay.privatekey');
        $publicKey = $this->container->getParameter('liqpay.publickey');
        $liqpay = new \LiqPay($publicKey, $privateKey);
        $check = base64_encode( sha1( $privateKey . $data . $privateKey, 1) );
        $data = $liqpay->decode_params($data);
        $uid = $data['order_id'];
        $status = $data['status'];
        if($check == $signature){
            if($status == 'sandbox' || $status == 'success') {
                $this->changeTicketStatusByUid($uid);
            } else {
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $status . PHP_EOL, FILE_APPEND);
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $uid . PHP_EOL, FILE_APPEND);
            }
        } else {
            file_put_contents(__DIR__. '/../../../../web/uploads/test', "$check = $signature" . PHP_EOL, FILE_APPEND);
        }
        return new Response();
    }

    protected function changeTicketStatusByUid($uid){
        $repository = $this->getDoctrine()->getRepository(SoldTicket::class);
        $tickets = $repository->findBy(
            ['uid' => $uid]
        );
        foreach ($tickets as $ticket){
            $ticket->setStatus(SoldTicket::STATUS_SOLD);
            $this->getManager()->persist($ticket);
            $this->getManager()->flush();
            $this->sendTicket($ticket);
        }
    }

    public function createPDF($soldTicket){
        $path = __DIR__ . '/../../../../web/uploads/tickets/' . $soldTicket->getId() . '.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('EventEventBundle:Event:ticketPDF.html.twig', [
                'soldTicket' => $soldTicket,
            ]),
            $path,
            [],
            true
        );
        return $path;
    }

    public function viewPDFHtmlAction(Request $request) {
        $soldTicket = $ticket = $this->findOr404('EventEventBundle:SOldTicket', 5);
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('EventEventBundle:Event:ticketPDF.html.twig', [
                'soldTicket' => $soldTicket,
            ]),
            __DIR__ . '/../../../../web/uploads/tickets/' . $soldTicket->getId() . '.pdf',
           [],
            true
        );
        return $this->render('EventEventBundle:Event:ticketPDF.html.twig', [
            'soldTicket' => $soldTicket,
        ]);
    }

    public function sendTicket(SoldTicket $soldTicket){
        $attachmentData = [
            'data' => $this->createPDF($soldTicket),
            'filename' => $soldTicket->getTicket()->getEvent()->getTitle() . ' ticket.pdf',
            'contentType' => 'application/pdf',
        ];

        $this->get('eventator_mailer')->sendWithPdfPathAttach(
            $soldTicket->getEmail(),
            'Your ticket for - ' . $soldTicket->getTicket()->getEvent()->getTitle(),
            $this->renderView('EventEventBundle:Email:_ticket.html.twig', [
                'soldTicket' => $soldTicket,
                'from' => $soldTicket->getTicket()->getEvent()->getEmail(),
                'languages' => $this->container->getParameter('event.speech_languages'),
                'levels' => $this->container->getParameter('event.speech_levels'),
            ]),
            $soldTicket->getTicket()->getEvent()->getEmail(),
            null,
            $attachmentData
        );

        return true;
    }

    public function ticketPaymentSuccessAction(){
        return $this->render('EventEventBundle:Event:ticketPaySuccess.html.twig', [
            'event'  => $this->getEvent(),
            'hosts'  => $this->getHostYear(),
        ]);
    }

    public function contactAction(Request $request)
    {
        $event = $this->getEvent();

        $form = $this->createForm(ContactType::class);
        if ($request->isMethod('POST') && $form->handleRequest($request)) {
            if ($form->isValid() && $request->getSession()->get('captchaResult') == $request->request->get('calc')) {
                $this->get('eventator_mailer')->send(
                    $event->getEmail(),
                    'Contact Request - '.$event->getTitle(),
                    sprintf(
                        'Hello!<br /><br />Contact request: %s <br />',
                        nl2br($form->get('message')->getData())
                    ),
                    $form->get('email')->getData()
                );

                $this->setSuccessFlash('Thank you for request, we\'ll answer back asap.');

                return $this->redirectToRoute('event_homepage');
            }
        }

        return $this->render('EventEventBundle:Component:_contact.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'captcha' => $this->getCaptcha()
        ]);
    }

    public function callForPaperAction(Request $request)
    {
        $entity = new CallForPaper();
        $event = $this->getEvent();
        $entity->setEvent($event);

        $form = $this->callForPaper($entity);
        $form->handleRequest($request);

        if ($form->isValid() && $request->getSession()->get('captchaResultCall') == $request->request->get('calc')) {
            $this->getManager()->persist($entity);
            $this->getManager()->flush();

            $this->get('eventator_mailer')->send(
                $event->getEmail(),
                'Call For Paper Request - '.$event->getTitle(),
                $this->renderView('EventEventBundle:Email:_callForPaper.html.twig', [
                    'data' => $request->request->get($form->getName()),
                    'from' => $form->get('email')->getData(),
                    'languages' => $this->container->getParameter('event.speech_languages'),
                    'levels' => $this->container->getParameter('event.speech_levels'),
                ]),
                $form->get('email')->getData()
            );

            return new Response('Success');

        }

        return new Response($this->renderView('EventEventBundle:Event:_form.html.twig', [
            'form' => $form->createView(),
            'captcha' => $this->getCaptcha('captchaResultCall')
        ]));
    }

    public function callForPaperViewAction()
    {
        $entity = new CallForPaper();
        $entity->setEvent($this->getEvent());
        $form = $this->callForPaper($entity);

        return new Response($this->renderView('EventEventBundle:Event:callForPaperView.html.twig', [
            'event' => $this->getEvent(),
            'hosts' => $this->getHostYear(),
            'form' => $form->createView(),
            'captcha' => $this->getCaptcha('captchaResultCall')
        ]));
    }

    public function blockMenuAction()
    {
        return new Response($this->renderView('@EventEvent/Component/_block_menu.html.twig', [
            'hosts' => $this->getHostYear(),
            'event' => $this->getEvent(),
            'home_page' => true
        ]));
    }

    public function getHostYear()
    {
        $hosts = $this->getRepository('EventEventBundle:Event')->getAllHosts();
        $year = [];

        foreach ($hosts as $host) {
            $year[] = array(
                'year' => preg_replace("/[^0-9]/", '', $host['host']),
                'host' => $host['host']
            );
        }

        return $year;
    }

    protected function callForPaper(CallForPaper $entity = null)
    {
        return $this->createForm(CallForPaperType::class, $entity);
    }
}
