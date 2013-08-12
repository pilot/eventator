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
        $event->addChild('Speakers', array('route' => 'backend_speaker'));
        $event->addChild('Speeches', array('route' => 'backend_speech'));
        $event->addChild('Schedule', array('route' => 'backend_program'));
        $event->addChild('Sponsors', array('route' => 'backend_sponsor'));

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
