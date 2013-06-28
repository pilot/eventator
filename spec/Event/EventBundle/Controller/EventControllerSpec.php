<?php

namespace spec\Event\EventBundle\Controller;

use PhpSpec\ObjectBehavior;

class EventControllerSpec extends ObjectBehavior
{
    function it_is_controller()
    {
        $this->shouldHaveType('Symfony\Bundle\FrameworkBundle\Controller\Controller');
    }
}