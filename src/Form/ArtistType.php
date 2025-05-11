<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Name (*)",
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
                    'label' => 'Description (*)',
                    'attr' => [
                        'rows' => '8'
                    ],
                ]
            )
            ->add('avatarFile', FileType::class, [
                'label' => 'Choose an image (*)',
            ])
            ->add(
                'twitch',
                TextType::class,
                [
                    'label' => "Twitch channel (Only write the username that's after : twitch.tv/) | Not mandatory",
                    'attr' => [
                        'placeholder' => 'twitch.tv/',
                    ],
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
            ->add(
                'twitter',
                TextType::class,
                [
                    'label' => "Twitter channel (Only write the username that's after : twitter.com/) | Not mandatory",
                    'attr' => [
                        'placeholder' => 'twitter.com/',
                    ],
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'attr' => [
                'novalidate' => 'novalidate', // Comment me to reactivate the html5 validation!  🚥
            ],
        ]);
    }
}
