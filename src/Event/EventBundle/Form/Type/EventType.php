<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\EventTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('host', UrlType::class)
            ->add('title', TextType::class)
            ->add('logo', TextType::class, ['required' => false])

            // carousel slides
            ->add('slideOne', TextType::class, ['required' => false])
            ->add('slideTwo', TextType::class, ['required' => false])
            ->add('slideThree', TextType::class, ['required' => false])

            ->add('briefDescription', TextareaType::class, [
                'label' => 'Brief Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('aboutDescription', TextareaType::class, [
                'label' => 'About Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10),
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Event Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 10)
            ])
            ->add('sponsorDescription', TextareaType::class, [
                'label' => 'Sponsor page Description',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 10],
                'required' => false
            ])
            ->add('sponsorGuide', TextType::class, [
                'label' => 'Sponsoring Guide paper',
                'required' => false
            ])
            ->add('country', CountryType::class, ['required' => false])
            ->add('state', TextType::class, ['required' => false])
            ->add('city', TextType::class, ['required' => false])
            ->add('startDate', DateType::class, [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'Start Date'
            ])
            ->add('endDate', DateType::class, [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'End Date'
            ])
            ->add('venue', TextType::class, ['label' => 'Venue Place'])
            ->add('venueAddress', TextareaType::class, [
                'label' => 'Venue Address',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
            ->add('longitude', TextType::class, ['required' => false])
            ->add('latitude', TextType::class, ['required' => false])

            // Event social profiles
            ->add('twitter', UrlType::class, ['required' => false])
            ->add('facebook', UrlType::class, ['required' => false])
            ->add('google', UrlType::class, ['required' => false])
            ->add('email', TextType::class, ['label' => 'Contact Email'])
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
            ->add('isActive', CheckboxType::class, ['required' => false])

            // Add translation
            ->add('translations', CollectionType::class, [
                'entry_type' => EventTranslationType::class
            ])
            ->add('metaTitle', TextType::class, ['required' => false])
            ->add('metaDesc', TextareaType::class, [
                'label' => 'Meta Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('metaKw', TextType::class, ['required' => false])
            ->add('ogTitle', TextType::class, ['required' => false])
            ->add('ogDesc', TextareaType::class, [
                'label' => 'OG Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('ogUrl', TextType::class, ['required' => false])
            ->add('ogImage', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Event'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'event';
    }
}
