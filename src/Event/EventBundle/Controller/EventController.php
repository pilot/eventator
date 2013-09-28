<?php

namespace Event\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent()
        ]);
    }

    public function speakersAction()
    {
        $form = $this->callForPaper();

        return $this->render('EventEventBundle:Component:_speakers.html.twig', [
            'speakers' => $this->getRepository('EventEventBundle:Speaker')->findAll(),
            'form' => $form->createView(),
        ]);
    }

    public function venueAction()
    {
        return $this->render('EventEventBundle:Component:_venue.html.twig', [
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent()
        ]);
    }

    public function scheduleAction()
    {
        return $this->render('EventEventBundle:Component:_schedule.html.twig', [
            'schedule' => $this->getRepository('EventEventBundle:Program')->findBy([], ['startDate' => 'ASC'])
        ]);
    }

    public function sponsorsAction()
    {
        return $this->render('EventEventBundle:Component:_sponsors.html.twig', [
            'event' => $this->getRepository('EventEventBundle:Event')->getEvent(),
            'sponsors' => $this->getRepository('EventEventBundle:Sponsor')->findAll()
        ]);
    }

    public function contactAction(Request $request)
    {
        $event = $this->getRepository('EventEventBundle:Event')->getEvent();

        $form = $this->createForm(new ContactType());
        if ($request->isMethod('POST') && $form->handleRequest($request)) {
            if ($form->isValid() && $this->getSession()->get('captchaResult') == $request->request->get('calc')) {
                $this->get('eventator_mailer')->send(
                    $event->getEmail(),
                    'Contact Request',
                    sprintf(
                        'Hello!<br /><br />Contact request: %s <br />',
                        nl2br($form->get('message')->getData())
                    ),
                    $form->get('email')->getData()
                );

                $this->setSuccessFlash('Thank you for request, we\'ll answer back asap.');

                return $this->redirect($this->generateUrl('event_homepage'));
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
        $event = $this->getRepository('EventEventBundle:Event')->getEvent();

        $form = $this->callForPaper();
        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->get('eventator_mailer')->send(
                $event->getEmail(),
                'Call For Paper Request',
                $this->renderView('EventEventBundle:Email:_callForPaper.html.twig', [
                    'data' => $form->getData(),
                    'from' => $form->get('email')->getData(),
                    'languages' => $this->container->getParameter('event.speech_languages'),
                    'levels' => $this->container->getParameter('event.speech_levels'),
                ]),
                $form->get('email')->getData()
            );

            return new Response('Success');
        }

        return new Response($this->renderView('EventEventBundle:Event:_form.html.twig', [
            'form' => $form->createView()
        ]));
    }

    protected function callForPaper()
    {
        return $this->createForm(new CallForPaperType([
            'languages' => $this->container->getParameter('event.speech_languages'),
            'levels' => $this->container->getParameter('event.speech_levels'),
        ]));
    }
}
