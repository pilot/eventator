<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpeakerTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', ['required' => false])
            ->add('lastName', 'text', ['required' => false])
            ->add('company', 'text', ['required' => false])
            ->add('position', 'text', ['required' => false])
            ->add('bio', 'textarea', [
                'label' => 'Brief Bio',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\SpeakerTranslation'
        ));
    }

    public function getName()
    {
        return 'speaker_translation';
    }
}
