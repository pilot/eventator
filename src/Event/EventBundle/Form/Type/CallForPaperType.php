<?php

namespace Event\EventBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class CallForPaperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['constraints' => [new NotBlank()]])
            ->add('email', 'email', ['constraints' => [new NotBlank(), new Email()]])
            ->add('twitter', 'text', ['required' => false])
            ->add('github', 'text', ['required' => false])
            ->add('title', 'text', ['constraints' => [new NotBlank()]])
            ->add('language', 'choice', [
                'choices' => ['English', 'Russian', 'Other']
            ])
            ->add('level', 'choice', [
                'label' => 'Talk level',
                'choices' => ['For all attendees', 'Intermediate', 'Experts']
            ])
            ->add('abstract', 'textarea', [
                'label' => 'Abstract of your talk',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'constraints' => [new NotBlank()]
            ])
            ->add('note', 'textarea', [
                'label' => 'Notes',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
        ;
    }

    public function getName()
    {
        return 'call_for_paper';
    }
}
