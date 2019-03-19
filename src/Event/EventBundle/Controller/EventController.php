<?php

namespace Event\EventBundle\Controller;

use Behat\Mink\Exception\ResponseTextException;
use Event\EventBundle\Entity\Discount;
use Event\EventBundle\Entity\SoldTicket;
use Event\EventBundle\Entity\SoldWorkshop;
use Event\EventBundle\Entity\Ticket;
use Event\EventBundle\Entity\Workshop;
use Event\EventBundle\Form\Type\SoldTicketType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Event\EventBundle\Entity\CallForPaper;
use Event\EventBundle\Form\Type\ContactType;
use Event\EventBundle\Form\Type\CallForPaperType;
use Symfony\Component\HttpFoundation\Cookie;

class EventController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventEventBundle:Event:index.html.twig', []);
    }

    public function changeLanguageAction(Request $request, $lang = null){
        if(is_null($lang)){
            $lang = $this->container->getParameter('locale');
        }
        $ref = $request->headers->get('referer');
        $response = new RedirectResponse($ref);
        $response->headers->setCookie(new Cookie('user_locale', $lang));

        return $response;
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

    public function workshopsAction()
    {
        $event = $this->getEvent();
        $workshops = $event->getWorkshops();
        $max = 0; 
        foreach ($workshops as $wsh){
            if($max < count($wsh->getSchedule())){
                $max = count($wsh->getSchedule());
            }
        }
        
        return $this->render('EventEventBundle:Component:_workshops.html.twig', [
            'event' => $event,
            'count' => $max
        ]);
    }

    public function updateTotalAjaxAction(Request $request){
        $discount = $request->request->get('discount');
        $count = $request->request->get('count');
        $discountAmount = 1;
        $discount = $this->getDoctrine()->getRepository(Discount::class)->findOneBy(['name' => $discount]);
        if($discount && $discount->isEnable($count)){
            $discountAmount = 1 - $discount->getDiscount() / 100;
        }
        $arrData = [
            'discount' => $discountAmount,
        ];
        return new JsonResponse($arrData);
    }

    public function buyTicketAction (Request $request){
        if(is_null( $request->request->get('ticket_id'))){
            return $this->redirect('/');
        }
        $ticket = $this->findOr404('EventEventBundle:Ticket', $request->request->get('ticket_id'));
        $label = Ticket::getCurrencyLabels($ticket->getCurrency());
        $total = $ticket->getPrice();
        $lunch_price = 0;
        $ap_price = 0;
        if($lunch = $request->request->get('lunch')){
            $lunch_price = $ticket->getLunchPrice();
        }
        if($ap = $request->request->get('after-party')){
            $ap_price = $ticket->getApPrice();
        }

        if ($request->isMethod('POST') && $sold_tickets = $request->request->get('soldTickets')) {
            $available = $ticket->getRemainingCount();

            $discountAmount = 1;
            $count = count($sold_tickets);
            if($available < $count) {
                return $this->render('EventEventBundle:Event:buyTicket.html.twig', [
                    'event'       => $this->getEvent(),
                    'ticket'      => $ticket,
                    'lunch'       => $lunch,
                    'ap'          => $ap,
                    'hosts'       => $this->getHostYear(),
                    'total'       => $total,
                    'lunch_price' => $lunch_price,
                    'ap_price'    => $ap_price,
                    'label'       => $label,
                    'error'       => "Only $available tickets are available.",
                ]);
            }
            if($discount = $request->request->get('discount')){
                $discount = $this->getDoctrine()->getRepository(Discount::class)->findOneBy(['name' => $discount]);
                if($discount && $discount->isEnable($count)){
                    $discountAmount = 1 - $discount->getDiscount() / 100;
                    $total *= $discountAmount;
                }
            }
            $total += $lunch_price;
            $total += $ap_price;
            $uid = time();

            foreach ($sold_tickets as $sold_ticket) {
                $entity = new SoldTicket();
                $entity->setTicket($ticket);
                $entity->setFirstName($sold_ticket['firstName']);
                $entity->setLastName($sold_ticket['lastName']);
                $entity->setCompany($sold_ticket['company']);
                $entity->setPosition($sold_ticket['position']);
                $entity->setCity($sold_ticket['city']);
                $entity->setEmail($request->request->get('email'));
                $entity->setPhone($request->request->get('phone'));
                $entity->setStatus(SoldTicket::STATUS_RESERVED);
                $entity->setUid($uid);
                $entity->setDateCreated(new \DateTime());
                $total = round($total, 2);
                $entity->setPrice($total);
                if($discount && $discount->isEnable($count)){
                    $entity->setDiscount($discount);
                }
                $entity->setLunch($lunch == true);
                $entity->setAp($ap == true);
                $this->getManager()->persist($entity);
                $this->getManager()->flush();
            }
            $public_key = $this->container->getParameter('liqpay.publickey');
            $private_key = $this->container->getParameter('liqpay.privatekey');
            $liqpay = new \LiqPay($public_key, $private_key);
            $amount = $total * count($sold_tickets);

            if($discountAmount == 0 && !$lunch && !$ap){
                $this->changeTicketStatusByUid($uid);
                return $this->redirectToRoute('tickets_payment_success');
            }

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
            'event'       => $this->getEvent(),
            'ticket'      => $ticket,
            'lunch'       => $lunch,
            'ap'          => $ap,
            'hosts'       => $this->getHostYear(),
            'total'       => $total,
            'lunch_price' => $lunch_price,
            'ap_price'    => $ap_price,
            'label'       => $label,
            'error'       => '',
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
            if($status == 'sandbox' || $status == 'success' || $status == 'wait_accept') {
                $this->changeTicketStatusByUid($uid, $status);
            } else {
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $status . PHP_EOL, FILE_APPEND);
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $uid . PHP_EOL, FILE_APPEND);
            }
        } else {
            file_put_contents(__DIR__. '/../../../../web/uploads/test', "$check = $signature" . PHP_EOL, FILE_APPEND);
        }
        return new Response();
    }

    protected function changeTicketStatusByUid($uid, $liqpayStatus = null){
        $repository = $this->getDoctrine()->getRepository(SoldTicket::class);
        $tickets = $repository->findBy(
            ['uid' => $uid]
        );
        foreach ($tickets as $ticket){
            $ticket->setStatus(SoldTicket::STATUS_SOLD);
            $ticket->setDateSold(new \DateTime());
            if($liqpayStatus){
                $ticket->setLiqpayStatus($liqpayStatus);
            }
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
        $soldTicket = $ticket = $this->findOr404('EventEventBundle:SoldTicket', 5);
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
            [$soldTicket->getTicket()->getEvent()->getEmail() => $this->container->getParameter('mail-from-name')],
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
            'captcha' => $this->getCaptcha('captchaResultCall'),
            'user_languages' => $this->container->getParameter('user.locales'),
            'default_lang' => $this->container->getParameter('locale')
        ]));
    }

    public function blockMenuAction($homepage = true)
    {
        return new Response($this->renderView('@EventEvent/Component/_block_menu.html.twig', [
            'hosts' => $this->getHostYear(),
            'event' => $this->getEvent(),
            'home_page' => $homepage,
            'user_languages' => $this->container->getParameter('user.locales'),
            'default_lang' => $this->container->getParameter('locale')
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

    public function buyWorkshopAction (Request $request){
        if(is_null( $request->request->get('wsh_id'))){
            return $this->redirectToRoute('event_homepage');
        }
        $workshop = $this->findOr404('EventEventBundle:Workshop', $request->request->get('wsh_id'));
        $label = Workshop::getCurrencyLabels($workshop->getCurrency());
        $total = $workshop->getPrice();

        if ($request->isMethod('POST') && $sold_workshops = $request->request->get('soldWorkshops')) {
            $available = $workshop->getRemainingCount();

           $count = count($sold_workshops);
            if($available < $count) {
                return $this->render('EventEventBundle:Event:buyWorkshop.html.twig', [
                    'event'       => $this->getEvent(),
                    'workshop'      => $workshop,
                    'hosts'       => $this->getHostYear(),
                    'total'       => $total,
                    'label'       => $label,
                    'error'       => "Only $available workshop tickets are available.",
                ]);
            }
            $uid = time();

            foreach ($sold_workshops as $sold_workshop) {
                $entity = new SoldWorkshop();
                $entity->setWorkshop($workshop);
                $entity->setFirstName($sold_workshop['firstName']);
                $entity->setLastName($sold_workshop['lastName']);
                $entity->setCompany($sold_workshop['company']);
                $entity->setPosition($sold_workshop['position']);
                $entity->setCity($sold_workshop['city']);
                $entity->setEmail($request->request->get('email'));
                $entity->setPhone($request->request->get('phone'));
                $entity->setStatus(SoldTicket::STATUS_RESERVED);
                $entity->setUid($uid);
                $entity->setDateCreated(new \DateTime());
                $total = round($total, 2);

                $this->getManager()->persist($entity);
                $this->getManager()->flush();
            }
            
            $public_key = $this->container->getParameter('liqpay.publickey');
            $private_key = $this->container->getParameter('liqpay.privatekey');
            $liqpay = new \LiqPay($public_key, $private_key);
            $amount = $total * count($sold_workshops);
            
            $liqpayData = array(
                'action'         => 'pay',
                'amount'         => $amount,
                'currency'       => $workshop->getCurrencylabel(),
                'description'    => 'buying workshop ticket(s)',
                'order_id'       => $uid,
                'version'        => '3',
                'server_url'     => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $this->generateUrl('workshops_handle_liqpay'),
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
                'amount' => $amount . ' ' . $workshop->getCurrencyLabel(),
                'count' => count($sold_workshops),
            ]);
        }
        return $this->render('EventEventBundle:Event:buyWorkshop.html.twig', [
            'event'       => $this->getEvent(),
            'workshop'    => $workshop,
            'hosts'       => $this->getHostYear(),
            'total'       => $total,
            'label'       => $label,
            'error'       => '',
        ]);
    }

    protected function changeWorkshopStatusByUid($uid, $liqpayStatus = null){
        $repository = $this->getDoctrine()->getRepository(SoldWorkshop::class);
        $tickets = $repository->findBy(
            ['uid' => $uid]
        );
        foreach ($tickets as $ticket){
            $ticket->setStatus(SoldWorkshop::STATUS_SOLD);
            $ticket->setDateSold(new \DateTime());
            if($liqpayStatus){
                $ticket->setLiqpayStatus($liqpayStatus);
            }
            $this->getManager()->persist($ticket);
            $this->getManager()->flush();
            $this->sendWshTicket($ticket);
        }
        return $tickets;
    }

    public function sendWshTicket(SoldWorkshop $soldWorkshop){
        $attachmentData = [
            'data' => $this->createWshPDF($soldWorkshop),
            'filename' => $soldWorkshop->getWorkshop()->getName() . '_ticket.pdf',
            'contentType' => 'application/pdf',
        ];

        $this->get('eventator_mailer')->sendWithPdfPathAttach(
            $soldWorkshop->getEmail(),
            'Your ticket for Workshop:  ' . $soldWorkshop->getWorkshop()->getName(),
            $this->renderView('EventEventBundle:Email:_workshop.html.twig', [
                'soldWorkshop' => $soldWorkshop,
                'from' => $soldWorkshop->getWorkshop()->getEvent()->getEmail(),
                'languages' => $this->container->getParameter('event.speech_languages'),
                'levels' => $this->container->getParameter('event.speech_levels'),
            ]),
            [$soldWorkshop->getWorkshop()->getEvent()->getEmail() => $this->container->getParameter('mail-from-name')],
            null,
            $attachmentData
        );

        return true;
    }

    public function createWshPDF(SoldWorkshop $soldWorkshop){
        $path = __DIR__ . '/../../../../web/uploads/workshops/' . $soldWorkshop->getId() . '.pdf';
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('EventEventBundle:Event:workshopPDF.html.twig', [
                'soldWorkshop' => $soldWorkshop,
            ]),
            $path,
            [],
            true
        );
        return $path;
    }

    public function viewWorkshopPDFHtmlAction(Request $request, $id) {
        $soldWorkshop = $this->findOr404('EventEventBundle:SoldWorkshop', $id);
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('EventEventBundle:Event:workshopPDF.html.twig', [
                'soldWorkshop' => $soldWorkshop,
            ]),
            __DIR__ . '/../../../../web/uploads/workshops/' . $soldWorkshop->getId() . '.pdf',
            [],
            true
        );
        return $this->render('EventEventBundle:Event:workshopPDF.html.twig', [
            'soldWorkshop' => $soldWorkshop,
        ]);
    }

    public function handleWorkshopLiqPayRequestAction(Request $request){
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
            if($status == 'sandbox' || $status == 'success' || $status == 'wait_accept') {
                $this->changeWorkshopStatusByUid($uid, $status);
            } else {
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $status . PHP_EOL, FILE_APPEND);
                file_put_contents(__DIR__. '/../../../../web/uploads/test', $uid . PHP_EOL, FILE_APPEND);
            }
        } else {
            file_put_contents(__DIR__. '/../../../../web/uploads/test', "$check = $signature" . PHP_EOL, FILE_APPEND);
        }
        return new Response();
    }

}
