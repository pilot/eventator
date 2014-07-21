<?php

namespace Event\EventBundle\Entity;

trait EventTrait
{
    /**
     * @var events
     *
     * @ORM\ManyToMany(targetEntity="Event")
     */
    private $events;

    public function __construct()
    {
        parent::_construct();

        $this->events = new ArrayCollection();
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add event
     *
     * @param Event $event
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }

    /**
     * Remove event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }
}
