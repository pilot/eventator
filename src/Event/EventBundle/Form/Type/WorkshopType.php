<?php

namespace Event\EventBundle\Form\Type;

use Event\EventBundle\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class WorkshopType extends AbstractType
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
                'choices'  => Workshop::getCurrencyLabels()
                ))
            ->add('date', DateType::class, [
                'attr' => ['class' => 'datepicker', "autocomplete" => "off"],
                'widget'   => 'single_text',
                'format' => 'dd/MM/y H:mm',
                'label' => 'Date'
            ])
            ->add('time', TextType::class, ['required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])
            ->add('count', TextType::class, ['required' => true])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Workshop'
        ));
    }

    public function getBlockPrefix()
    {
        return 'workshop';
    }
}
