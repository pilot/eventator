<?php

namespace Event\EventBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function dashboardNavigation(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Dashboard', array('route' => 'backend_dashboard'));

        return $menu;
    }

    public function sideBar(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-list nav-stacked');
        $menu->setChildrenAttribute('style', 'font-size: 13px');

        $event = $menu->addChild('Event');
        $event->addChild('Settings', array('route' => 'backend_setting'));
        $event->addChild('Events', array('route' => 'backend_event'));
        $event->addChild('Speakers', array('route' => 'backend_speaker'));
        $event->addChild('Speeches', array('route' => 'backend_speech'));
        $event->addChild('Schedule', array('route' => 'backend_program'));
        $event->addChild('Sponsors', array('route' => 'backend_sponsor'));
        $event->addChild('Organizers', array('route' => 'backend_organizer'));
        $event->addChild('Calls For Paper', array('route' => 'backend_call_for_paper'));
        $event->addChild('Tickets', array('route' => 'backend_ticket'));
        $event->addChild('Sold tickets', array('route' => 'backend_sold_ticket'));
        $event->addChild('Discount', array('route' => 'backend_discount'));
        $event->addChild('Workshops', array('route' => 'backend_workshop'));
        $event->addChild('Sold workshops', array('route' => 'backend_sold_workshop'));

        return $menu;
    }
}
