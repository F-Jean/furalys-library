<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
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
                $image = $event->getData();
                if($image === null) 
                {
                    $event->getForm()->add('file', FileType::class, [
                        'label' => false,
                        'required' => false,
                    ]);
                }
            })
            ->add(
                'releasedThe',
                DateTimeType::class,
                [
                    'label' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
