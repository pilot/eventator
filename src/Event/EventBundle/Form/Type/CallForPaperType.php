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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CallForPaperType extends AbstractType
{
    protected $languages;

    protected $levels;

    public function __construct(array $languages, array $levels)
    {
        $this->languages = $languages;
        $this->levels = $levels;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [new NotBlank()]])
            ->add('email', EmailType::class, ['constraints' => [new NotBlank(), new Email()]])
            ->add('twitter', TextType::class, ['required' => false])
            ->add('github', TextType::class, ['required' => false])
            ->add('title', TextType::class, ['constraints' => [new NotBlank()]])
            ->add('language', ChoiceType::class, [
                'choices' => $options['languages'],
                'choices_as_values' => true,
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Talk level',
                'choices' => $options['levels'],
                'choices_as_values' => true,
            ])
            ->add('abstract', TextareaType::class, [
                'label' => 'Abstract of your talk',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'constraints' => [new NotBlank()]
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Notes',
                'attr' => ['class' => 'input-xxlarge', 'rows' => 5],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Event\EventBundle\Entity\CallForPaper',
            'languages' => array_flip($this->languages),
            'levels' => array_flip($this->levels),
        ]);
    }

    public function getBlockPrefix()
    {
        return 'call_for_paper';
    }
}
