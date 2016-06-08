<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Entity\Sponsor;
use Event\EventBundle\Form\Type\Translation\SponsorTranslationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SponsorType extends AbstractType
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
            ->add('type', ChoiceType::class, [
                'choices' => $options['sponsor_types'],
                'placeholder' => 'Choose Type',
                'choices_as_values' => true,
            ])
            ->add('company', TextType::class)
            ->add('description', TextareaType::class, [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('logo', TextType::class, ['required' => false])
            ->add('homepage', UrlType::class, ['required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])

            // Add translation
            ->add('translations', CollectionType::class, array(
                'entry_type' => SponsorTranslationType::class
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Sponsor',
            'sponsor_types' => array_flip(Sponsor::$types),
        ]);
    }

    public function getBlockPrefix()
    {
        return 'sponsor';
    }
}
