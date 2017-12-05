<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ShowBlocksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('showWhereItBeSection', CheckboxType::class, ['required' => false])
            ->add('showSpeakersSection', CheckboxType::class, ['required' => false])
            ->add('showScheduleSection', CheckboxType::class, ['required' => false])
            ->add('showAboutSection', CheckboxType::class, ['required' => false])
            ->add('showVenueSection', CheckboxType::class, ['required' => false])
            ->add('showMapSection', CheckboxType::class, ['required' => false])
            ->add('showHowItWasSection', CheckboxType::class, ['required' => false])
            ->add('showSponsorsSection', CheckboxType::class, ['required' => false])
            ->add('showOrganizersSection', CheckboxType::class, ['required' => false])
            ->add('showContactSection', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\ShowBlocks'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'show_blocks';
    }
}
