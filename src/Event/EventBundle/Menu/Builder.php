<?php

namespace Event\EventBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function dashboardNavigation(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Dashboard', array('route' => 'backend_dashboard'));

        return $menu;
    }

    public function sideBar(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-list nav-stacked');

        $event = $menu->addChild('Event');
        $event->addChild('Settings', array('route' => 'backend_setting'));
        $event->addChild('Program', array('route' => 'backend_program'));
        $event->addChild('Speaker', array('route' => 'backend_speaker'));
        $event->addChild('Sponsor', array('route' => 'backend_sponsor'));

        return $menu;
    }

    /**
     * Event frontend menu's
     */
    public function topMenu(FactoryInterface $factory, array $options)
    {
        $request = $this->container->get('request');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('About Event', array('route' => 'event_homepage'));
        $menu->addChild('Program', array('route' => 'event_homepage'));
        $menu->addChild('Speakers', array('route' => 'event_homepage'));
        $menu->addChild('Sponsors', array('route' => 'event_homepage'));
        $menu->addChild('Contact us', array('route' => 'event_homepage'));

        return $menu;
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private function getSecurityContext()
    {
        return $this->container->get('security.context');
    }
}
