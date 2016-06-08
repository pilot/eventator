<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\SpeechTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SpeechType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('speaker', EntityType::class, [
                'class' => 'EventEventBundle:Speaker',
                'choice_label' => 'fullName',
                'placeholder' => 'Choose Speaker'
            ])
            // @todo: implement dynamic relation between speaker/event/speech
            ->add('events', EntityType::class, [
                'class' => 'EventEventBundle:Event',
                'placeholder' => 'Choose Event',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('title', TextType::class)
            ->add('language', LanguageType::class, [
                'label' => 'Speech language',
                'preferred_choices' => ['en', 'ru', 'de']
            ])
            ->add('description', TextareaType::class, [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('slide', TextareaType::class, [
                'label' => 'Slides embed code',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('video', TextareaType::class, [
                'label' => 'Video embed code',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])

            // Add translation
            ->add('translations', CollectionType::class, array(
                'entry_type' => SpeechTranslationType::class
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Speech'
        ));
    }

    public function getBlockPrefix()
    {
        return 'speech';
    }
}
