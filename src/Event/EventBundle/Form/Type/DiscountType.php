<?php

namespace Event\EventBundle\Form\Type;

use Event\EventBundle\Entity\Discount;
use Event\EventBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Event\EventBundle\Form\Type\Translation\SpeakerTranslationType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('discount', IntegerType::class , [
                'label' => 'Discount, %',
                'attr' => array('min' => 1, 'max' => 100),
            ])
            ->add('type', ChoiceType::class, array(
                'choices'  => Discount::getTypeLabel(),
                ))
            ->add('amount', TextType::class, ['label' => 'Count', 'required' => false])
            ->add('dateTo', DateType::class , ['label' => 'To date','required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Event\EventBundle\Entity\Discount'
        ));
    }

    public function getBlockPrefix()
    {
        return 'discount';
    }
}
