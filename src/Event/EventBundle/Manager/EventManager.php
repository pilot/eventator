<?php

namespace Event\EventBundle\Manager;

use Event\EventBundle\Entity\Event;

class EventManager
{
    private $currentEvent;

    public function getCurrentEvent()
    {
        return $this->currentEvent;
    }

    public function setCurrentEvent(Event $event)
    {
        $this->currentEvent = $event;
    }
}
