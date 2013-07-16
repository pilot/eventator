<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // social profiles
            ->add('twitter', 'text', array('required' => false))
            ->add('facebook', 'text', array('required' => false))
            ->add('google', 'text', array('required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'inherit_data' => true
        ]);
    }

    public function getName()
    {
        return 'social';
    }
}
