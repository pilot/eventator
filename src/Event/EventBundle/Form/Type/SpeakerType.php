<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpeakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', ['label' => 'First Name'])
            ->add('lastName', 'text', ['label' => 'Last Name'])
            ->add('company', 'text', ['required' => false])
            ->add('position', 'text', ['required' => false])
            ->add('bio', 'textarea', [
                'label' => 'Brief Bio',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
            ->add('email', 'text', ['required' => false])
            ->add('homepage', 'url', ['required' => false])
            ->add('github', 'text', ['label' => 'GitHub.com username', 'required' => false])

            // Event social profiles
            ->add('social', new SocialType(), ['data_class' => 'Event\EventBundle\Entity\Speaker'])

            // Add translation
            ->add('translations', 'collection', array(
                'type' => new SpeakerTranslationType()
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Speaker'
        ));
    }

    public function getName()
    {
        return 'speaker';
    }
}
