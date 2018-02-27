<?php

namespace Event\EventBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Event\EventBundle\Entity\Repository\EventRepository;
use Event\EventBundle\Manager\EventManager;

class InitListener implements EventSubscriberInterface
{
    private $eventRepository;
    private $eventManager;

    public function __construct(EventRepository $eventRepository, EventManager $eventManager)
    {
        $this->eventRepository = $eventRepository;
        $this->eventManager = $eventManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->cookies->has('user_locale')) {
            $locale = $request->cookies->get('user_locale');
            $request->setLocale($locale);
        }
        $host = $request->getHttpHost();
        $event = $this->eventRepository->findOneBy(['host' => 'http://'.$host]);

        if (!$event) {
            throw new NotFoundHttpException(sprintf('No event for host "%s" found', $host));
        }

        $this->eventManager->setCurrentEvent($event);

        // set event for frontend page
        $request->attributes->set('event', $event);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 32)),
        );
    }
}
