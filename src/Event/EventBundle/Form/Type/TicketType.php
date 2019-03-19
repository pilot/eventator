<?php

namespace Event\EventBundle\Form\Type;

use Event\EventBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\SpeakerTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event', EntityType::class, [
                'class' => 'EventEventBundle:Event',
                'multiple' => false,
            ])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('price', TextType::class, ['label' => 'Price'])
            ->add('currency', ChoiceType::class, array(
                'choices'  => Ticket::getCurrencyLabels()
                ))
            ->add('lunch_price', TextType::class, ['required' => false])
            ->add('ap_price', TextType::class, ['required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])
            ->add('count', TextType::class, ['required' => true])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Ticket'
        ));
    }

    public function getBlockPrefix()
    {
        return 'ticket';
    }
}
