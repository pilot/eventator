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
            ->add('title', 'text', ['required' => false])
            ->add('briefDescription', 'textarea', [
                'label' => 'Brief Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
            ->add('description', 'textarea', [
                'label' => 'Event Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('sponsorDescription', 'textarea', [
                'label' => 'Sponsor page Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('state', 'text', ['required' => false])
            ->add('city', 'text', ['required' => false])
            ->add('venue', 'text', [
                'label' => 'Venue Place',
                'required' => false
            ])
            ->add('venueAddress', 'textarea', [
                'label' => 'Venue Address',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
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
            'data_class' => 'Event\EventBundle\Entity\Translation\EventTranslation',
        ));
    }

    public function getName()
    {
        return 'settings_translation';
    }
}
