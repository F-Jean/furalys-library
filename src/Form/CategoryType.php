<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => "Title",
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'empty_data' => '',
                    'label' => 'Description',
                    'attr' => [
                        'rows' => '8'
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'attr' => [
                'novalidate' => 'novalidate', // Comment me to reactivate the html5 validation!  🚥
            ],
        ]);
    }
}
