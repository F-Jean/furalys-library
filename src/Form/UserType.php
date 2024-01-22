<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                TextType::class,
                [
                    'label' => "Email",
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'label' => "Username",
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Passwords don\'t match',
                    'required' => true,
                    'first_options'  => [
                        'label' => 'Password',
                        'attr' => [
                            'placeholder' => 'Type your password here ...',
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Confirm password',
                        'attr' => [
                            'placeholder' => 'Confirm your password here ...',
                        ],
                    ],
                    'empty_data' => '',
                ]
            )
            ->add(
                'role',
                ChoiceType::class,
                [
                    'required' => true,
                    'multiple' => false,
                    'label' => 'Rôle de l\'utilisateur',
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                    ],
                    'attr' => [
                        'class' => 'w-25',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate', // Comment me to reactivate the html5 validation!  🚥
            ],
        ]);
    }
}
