<?php

namespace Event\EventBundle\Form\Type\Translation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => false])
            ->add('briefDescription', TextareaType::class, [
                'label' => 'Brief Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
            ->add('aboutDescription', TextareaType::class, [
                'label' => 'About Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Event Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('sponsorDescription', TextareaType::class, [
                'label' => 'Sponsor page Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('state', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['required' => false])
            ->add('venue', TextType::class, [
                'label' => 'Venue Place',
                'required' => false
            ])
            ->add('venueAddress', TextareaType::class, [
                'label' => 'Venue Address',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
            ->add('contact', TextareaType::class, [
                'label' => 'Additional Contact Information',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('embedTicket', TextareaType::class, [
                'label' => 'Tickets Provider embed code',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Translation\EventTranslation',
        ));
    }

    public function getBlockPrefix()
    {
        return 'event_translation';
    }
}
