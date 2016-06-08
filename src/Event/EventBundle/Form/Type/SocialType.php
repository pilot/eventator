<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // social profiles
            ->add('twitter', TextType::class, array('required' => false))
            ->add('facebook', TextType::class, array('required' => false))
            ->add('google', TextType::class, array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Speaker',
            'inherit_data' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return 'social';
    }
}
