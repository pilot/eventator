<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea', [
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5]
            ])
        ;
    }

    public function getName()
    {
        return 'contact';
    }
}
