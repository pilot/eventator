<?php

namespace Event\EventBundle\Form\Type\Translation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SponsorTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, ['required' => false])
            ->add('description', TextareaType::class, [
                'attr' => array('class' => 'input-xxlarge', 'rows' => 5),
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Translation\SponsorTranslation'
        ));
    }

    public function getBlockPrefix()
    {
        return 'sponsor_translation';
    }
}
