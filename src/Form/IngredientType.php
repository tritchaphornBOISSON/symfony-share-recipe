<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $buttonLabel = $options['button_label'];

        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Ingredient name',
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(200),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $buttonLabel,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
            'button_label' => 'Add my ingredient',
        ]);
    }
}
