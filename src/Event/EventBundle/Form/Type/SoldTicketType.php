<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SoldTicketType extends AbstractType
{
    public function getCountChoices($count = 15){
        $choices = [];
        for ($i=1; $i<=$count; $i++){
            $choices[$i] = $i;
        }
        return $choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, ['required' => true])
            ->add('lastName', TextType::class, ['required' => true])
            ->add('company', TextType::class)
            ->add('position', TextType::class)
            ->add('city', TextType::class)
            ->add('email', EmailType::class, ['required' => true])
            ->add('count', ChoiceType::class, [
                'choices' => $this->getCountChoices(),
                'attr' => ['class' => 'active'],
                'choices_as_values' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\SoldTicket',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'soldTickets';
    }
}
