<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => false))
            ->add('briefDescription', 'textarea', array(
                'label' => 'Brief Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ))
            ->add('description', 'textarea', array(
                'label' => 'Event Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10),
                'required' => false
            ))
            ->add('sponsorDescription', 'textarea', array(
                'label' => 'Sponsor page Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10),
                'required' => false
            ))
            ->add('state', 'text', array('required' => false))
            ->add('city', 'text', array('required' => false))
            ->add('venue', 'textarea', array(
                'label' => 'Event Venue',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ))
            ->add('contact', 'textarea', [
                'label' => 'Additional Contact Information',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('embedTicket', 'textarea', [
                'label' => 'Tickets Provider embed code',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\EventTranslation',
        ));
    }

    public function getName()
    {
        return 'settings_translation';
    }
}
