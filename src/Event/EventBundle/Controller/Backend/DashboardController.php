<?php

namespace Event\EventBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Event\EventBundle\Controller\Controller;
use Event\EventBundle\Form\Type\SettingsType;
use Event\EventBundle\Entity\Event;
use Event\EventBundle\Entity\EventTranslation;
use Event\EventBundle\Entity\Speaker;
use Event\EventBundle\Entity\SpeakerTranslation;

class DashboardController extends Controller
{
    public function indexAction()
    {
        // @todo: handle init on kernel listener
        $this->initEvent();

        return $this->render('EventEventBundle:Backend:index.html.twig', []);
    }

    public function settingAction(Request $request)
    {
        $event = $this->getRepository('EventEventBundle:Event')->getEvent();

        if (!$event) {
            $event = new Event();
        }

        $form = $this->createForm(new SettingsType(), $event);
        if ($request->isMethod('POST') && $form->handleRequest($request)) {

            if ($form->isValid()) {

                $em = $this->getManager();
                $em->persist($event);
                $em->flush();

                $this->setSuccessFlash('Event settings updated.');

                return $this->redirect($this->generateUrl('backend_setting'));
            }
        }

        return $this->render('EventEventBundle:Backend:setting.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function localeTabsAction()
    {
        return $this->render('EventEventBundle:Backend:_tabs.html.twig', [
            'locales' => $this->container->getParameter('event.locales')
        ]);
    }

    /**
     * Event initialization
     *
     * This is a workaround to stay bundle independent from fixtures, or cli init
     * Feel free to remove this call after init or switch to the fixtures
     */
    protected function initEvent()
    {
        $locales = $this->container->getParameter('event.locales');
        $event = $this->getRepository('EventEventBundle:Event')->getEvent();
        $now = new \DateTime();

        if (!$event) {
            $event = new Event();
            $event
                ->setTitle('My Event')
                ->setDescription('My another awesome event!')
                ->setStartDate($now)
                ->setEndDate($now->modify('+1 day'))
                ->setVenue('Burj Khalifa Tower')
            ;

            $speaker = new Speaker();
            $speaker
                ->setFirstName('Phill')
                ->setLastName('Pilow')
                ->setCompany('Reseach Supplier')
            ;

            if ($locales) {
                foreach ($locales as $locale => $title) {
                    $eventTranslation = new EventTranslation();
                    $eventTranslation->setEvent($event);
                    $eventTranslation->setlocale($locale);

                    $this->getManager()->persist($eventTranslation);

                    $speakerTranslation = new SpeakerTranslation();
                    $speakerTranslation->setSpeaker($speaker);
                    $speakerTranslation->setlocale($locale);

                    $this->getManager()->persist($speakerTranslation);
                }
            }

            $this->getManager()->persist($event);
            $this->getManager()->persist($speaker);
            $this->getManager()->flush();
        }
    }

    /**
     * Handle that new locales was added to the configuration, to init it
     */
    protected function addLocales()
    {
        // @todo: add locales when locales configuration is updated
    }
}
