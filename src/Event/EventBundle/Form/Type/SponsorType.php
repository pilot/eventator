<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Event\EventBundle\Entity\Sponsor;
use Event\EventBundle\Form\Type\Translation\SponsorTranslationType;

class SponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('events', 'entity', [
                'class' => 'EventEventBundle:Event',
                'empty_value' => 'Choose Event',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('type', 'choice', [
                'choices' => Sponsor::$types,
                'empty_value' => 'Choose Type'
            ])
            ->add('company', 'text')
            ->add('description', 'textarea', [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('logo', 'text', ['required' => false])
            ->add('homepage', 'url', ['required' => false])
            ->add('isActive', 'checkbox', ['required' => false])

            // Add translation
            ->add('translations', 'collection', array(
                'type' => new SponsorTranslationType()
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Sponsor'
        ));
    }

    public function getName()
    {
        return 'sponsor';
    }
}
