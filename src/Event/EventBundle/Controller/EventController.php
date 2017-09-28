<?php

namespace Event\EventBundle\Controller;

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
            'form' => $form->createView(),
            'captcha' => $this->getCaptcha('captchaResultCall')
        ]));
    }

    protected function callForPaper(CallForPaper $entity = null)
    {
        return $this->createForm(CallForPaperType::class, $entity);
    }
}
