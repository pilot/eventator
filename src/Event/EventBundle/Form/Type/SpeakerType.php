<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\SpeakerTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SpeakerType extends AbstractType
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
            ->add('firstName', TextType::class, ['label' => 'First Name'])
            ->add('lastName', TextType::class, ['label' => 'Last Name'])
            ->add('photo', TextType::class, ['required' => false])
            ->add('company', TextType::class, ['required' => false])
            ->add('position', TextType::class, ['required' => false])
            ->add('bio', TextareaType::class, [
                'label' => 'Brief Bio',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('email', TextType::class, ['required' => false])
            ->add('homepage', UrlType::class, ['required' => false])
            ->add('github', TextType::class, ['label' => 'GitHub.com username', 'required' => false])

            // Event social profiles
            ->add('social', SocialType::class)

            // Add translation
            ->add('translations', CollectionType::class, array(
                'entry_type' => SpeakerTranslationType::class
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Speaker'
        ));
    }

    public function getBlockPrefix()
    {
        return 'speaker';
    }
}
