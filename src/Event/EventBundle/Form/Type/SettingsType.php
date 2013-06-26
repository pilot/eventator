<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('briefDescription', 'textarea', array(
                'label' => 'Brief Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ))
            ->add('description', 'textarea', array(
                'label' => 'Event Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10)
            ))
            ->add('country', 'country', array('required' => false))
            ->add('state', 'text', array('required' => false))
            ->add('city', 'text', array('required' => false))
            ->add('startDate', 'date', array(
                'attr' => array('class' => 'datepicker input-small'),
                'widget'   => 'single_text',
                'format' => 'MM/dd/y',
                'label' => 'Start Date'
            ))
            ->add('endDate', 'date', array(
                'attr' => array('class' => 'datepicker input-small'),
                'widget'   => 'single_text',
                'format' => 'MM/dd/y',
                'label' => 'End Date'
            ))
            ->add('venue', 'textarea', array(
                'label' => 'Event Venue',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5)
            ))
            ->add('longitude', 'text', array('required' => false))
            ->add('latitude', 'text', array('required' => false))
            
            // Event social profiles
            ->add('twitter', 'text', array('required' => false))
            ->add('facebook', 'text', array('required' => false))
            ->add('google', 'text', array('required' => false))

            // Add translation
            ->add('translations', 'collection', array(
                'type' => new SettingsTranslationType(),
                'allow_add' => true
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Event',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'settings';
    }
}
