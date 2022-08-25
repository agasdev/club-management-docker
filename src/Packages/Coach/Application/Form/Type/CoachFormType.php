<?php

namespace App\Packages\Coach\Application\Form\Type;

use App\Packages\Coach\Application\DTO\CoachDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoachFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'input' => 'string'
            ])
            ->add('city', TextType::class)
            ->add('country', TextType::class)
            ->add('salary', IntegerType::class)
            ->add('email', EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoachDto::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function getName(): string
    {
        return '';
    }
}