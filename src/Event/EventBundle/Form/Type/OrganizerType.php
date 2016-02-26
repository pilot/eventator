<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Entity\Organizer;
use Event\EventBundle\Form\Type\Translation\OrganizerTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrganizerType extends AbstractType
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
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('logo', TextType::class, ['required' => false])
            ->add('homepage', UrlType::class, ['required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])

            // Add translation
            ->add('translations', CollectionType::class, array(
                'entry_type' => OrganizerTranslationType::class
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Organizer'
        ));
    }

    public function getBlockPrefix()
    {
        return 'organizer';
    }
}
