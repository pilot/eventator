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

        $event = $menu->addChild('Event');
        $event->addChild('Settings', array('route' => 'backend_setting'));
        $event->addChild('Events', array('route' => 'backend_event'));
        $event->addChild('Speakers', array('route' => 'backend_speaker'));
        $event->addChild('Speeches', array('route' => 'backend_speech'));
        $event->addChild('Schedule', array('route' => 'backend_program'));
        $event->addChild('Sponsors', array('route' => 'backend_sponsor'));
        $event->addChild('Organizers', array('route' => 'backend_organizer'));

        return $menu;
    }
}
