<?php

namespace Event\EventBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;
use Event\EventBundle\Entity\Repository\EventRepository;
use Event\EventBundle\Manager\EventManager;
use Event\EventBundle\Entity\Event;
use Event\EventBundle\Entity\Translation\EventTranslation;
use Event\EventBundle\Entity\Speaker;
use Event\EventBundle\Entity\Translation\SpeakerTranslation;

class InitListener implements EventSubscriberInterface
{
    private $eventRepository;
    private $eventManager;
    private $entityManager;

    public function __construct(EventRepository $eventRepository, EventManager $eventManager, ObjectManager $entityManager)
    {
        $this->eventRepository = $eventRepository;
        $this->eventManager = $eventManager;
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(GetResponseEvent $onEvent)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $onEvent->getRequestType()) {
            return;
        }

        $event = $this->checkEvent($onEvent);

        if (!$event) {
            // Create Event for requested host name
            $event = $this->initEvent();
        }

        $this->eventManager->setCurrentEvent($event);

        // set event for homepage page template
        $onEvent->getRequest()->attributes->set('event', $event);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 32)),
        );
    }

    protected function checkEvent(GetResponseEvent $onEvent)
    {
        $request = $onEvent->getRequest();

        if ($request->cookies->has('user_locale')) {
            $locale = $request->cookies->get('user_locale');
            $request->setLocale($locale);
        }

        return $this
            ->eventRepository
            ->findOneBy(['host' => $request->getHttpHost()]);
    }

    /**
     * Event initialization
     *
     * This is a workaround to stay bundle independent from fixtures, or cli init
     * Feel free to remove this call after init or switch to the fixtures
     */
    protected function initEvent()
    {
        // en_EN locale is default!
        // @todo replace it with event_event.locales from config.yml
        $locales = ['ru_RU' => 'ru', 'de_DE' => 'de'];
        $now = new \DateTime();
        
        $event = new Event();
        $event
            ->setHost('localhost')
            ->setTitle('My Event')
            ->setDescription('My another awesome event!')
            ->setStartDate($now)
            ->setEndDate($now->modify('+1 day'))
            ->setVenue('Burj Khalifa Tower')
            ->setEmail('hello@lazy-ants.com')
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

                $this->entityManager->persist($eventTranslation);

                $speakerTranslation = new SpeakerTranslation();
                $speakerTranslation->setSpeaker($speaker);
                $speakerTranslation->setlocale($locale);

                $this->entityManager->persist($speakerTranslation);
            }
        }

        $this->entityManager->persist($event);
        $this->entityManager->persist($speaker);
        $this->entityManager->flush();
        
        return $event;
    }
}
