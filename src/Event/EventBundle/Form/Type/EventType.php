<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('logo', 'text', ['required' => false])

            // carousel slides
            ->add('slideOne', 'text', ['required' => false])
            ->add('slideTwo', 'text', ['required' => false])
            ->add('slideThree', 'text', ['required' => false])

            ->add('briefDescription', 'textarea', [
                'label' => 'Brief Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('description', 'textarea', [
                'label' => 'Event Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10)
            ])
            ->add('sponsorDescription', 'textarea', [
                'label' => 'Sponsor page Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('sponsorGuide', 'text', [
                'label' => 'Sponsoring Guide paper',
                'required' => false
            ])
            ->add('country', 'country', ['required' => false])
            ->add('state', 'text', ['required' => false])
            ->add('city', 'text', ['required' => false])
            ->add('startDate', 'date', [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'Start Date'
            ])
            ->add('endDate', 'date', [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'End Date'
            ])
            ->add('venue', 'text', ['label' => 'Venue Place'])
            ->add('venueAddress', 'textarea', [
                'label' => 'Venue Address',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
            ->add('longitude', 'text', ['required' => false])
            ->add('latitude', 'text', ['required' => false])

            // Event social profiles
            ->add('twitter', 'url', ['required' => false])
            ->add('facebook', 'url', ['required' => false])
            ->add('google', 'url', ['required' => false])
            ->add('email', 'text', ['label' => 'Contact Email'])
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
            ->add('isActive', 'checkbox', ['required' => false])

            // Add translation
            ->add('translations', 'collection', [
                'type' => new EventTranslationType()
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Event'
        ]);
    }

    public function getName()
    {
        return 'settings';
    }
}
