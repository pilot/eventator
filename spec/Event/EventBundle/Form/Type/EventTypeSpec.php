<?php

namespace spec\Event\EventBundle\Form\Type;

use PhpSpec\ObjectBehavior;

class EventTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_has_valid_name()
    {
        $this->getName()->shouldReturn('settings');
    }
}
