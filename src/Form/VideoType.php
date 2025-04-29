<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'path',
                HiddenType::class
                // displayed in the form as a hidden field
            )
            // The event is used here to dynamically add a 'file' field to the form 
            // based on the presence or absence of an existing image
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event)
            {
                $video = $event->getData();
                if($video === null) 
                {
                    $event->getForm()->add('file', FileType::class, [
                        'label' => false,
                        'required' => false,
                    ]);
                }
            })
            ->add(
                'url',
                TextType::class,
                [
                    'label' => false,
                    /* Ajout de empty_data pour test le blank pour l'edit
                    (et ne pas avoir l'erreur de sf) */
                    'empty_data' => '',
                ]
            )
            ->add(
                'releasedThe',
                DateType::class,
                [
                    'label' => false,
                    'widget' => 'single_text',
                    'html5' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
