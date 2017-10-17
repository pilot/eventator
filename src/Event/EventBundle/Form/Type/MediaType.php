<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('filename', HiddenType::class, ['required' => false])
            ->add('description', TextareaType::class, [
                'label' => 'Media Description',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 3),
                'required' => false
            ])
            ->add('copyrightInfo', TextareaType::class, [
                'label' => 'Copyright Info',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 3),
                'required' => false
            ])
            ->add('mediaCredits', TextareaType::class, [
                'label' => 'Media Credits',
                'attr' => array('class' => 'input-xxlarge', 'rows' => 3),
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\Media'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'media';
    }
}
