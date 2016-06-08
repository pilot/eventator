<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\ProgramTranslationType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('events', EntityType::class, [
                'class' => 'EventEventBundle:Event',
                'placeholder' => 'Choose Event',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('isTopic', CheckboxType::class, [
                'label' => 'Topic?',
                'required' => false
            ])
            ->add('speech', EntityType::class, [
                'class' => 'EventEventBundle:Speech',
                'choice_label' => 'title',
                'placeholder' => 'Choose Speech',
                'required' => false
            ])
            ->add('title', TextType::class, ['required' => false])
            ->add('link', UrlType::class, ['required' => false])
            ->add('startDate', DateType::class, [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'Start Time'
            ])
            ->add('endDate', DateType::class, [
                'attr' => ['class' => 'datepicker input-medium'],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'End Time'
            ])

            // Add translation
            ->add('translations', CollectionType::class, [
                'entry_type' => ProgramTranslationType::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Program'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'program';
    }
}
