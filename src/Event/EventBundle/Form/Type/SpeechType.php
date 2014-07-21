<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Event\EventBundle\Form\Type\Translation\SpeechTranslationType;

class SpeechType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('speaker', 'entity', [
                'class' => 'EventEventBundle:Speaker',
                'property' => 'fullName',
                'empty_value' => 'Choose Speaker'
            ])
            // @todo: implement dynamic relation between speaker/event/speech
            ->add('events', 'entity', [
                'class' => 'EventEventBundle:Event',
                'empty_value' => 'Choose Event',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('title', 'text')
            ->add('language', 'language', [
                'label' => 'Speech language',
                'preferred_choices' => ['en', 'ru', 'de']
            ])
            ->add('description', 'textarea', [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('slide', 'textarea', [
                'label' => 'Slides embed code',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('video', 'textarea', [
                'label' => 'Video embed code',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])

            // Add translation
            ->add('translations', 'collection', array(
                'type' => new SpeechTranslationType()
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Speech'
        ));
    }

    public function getName()
    {
        return 'speech';
    }
}
