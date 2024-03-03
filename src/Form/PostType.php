<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $filteredArtists = $options['filtered_Artists'];
        $filteredCategories = $options['filtered_Categories'];

        $builder
            ->add(
                'artists',
                EntityType::class, 
                [
                    'class' => Artist::class,
                    'choice_label' => 'name',
                    'label' => 'Choose one or more artist(s) :',
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $filteredArtists,
                    
                ]
            )
            ->add(
                'categories', 
                EntityType::class, 
                [
                    'class' => Category::class,
                    'choice_label' => 'title',
                    'label' => 'Choose one or more category(ies) :',
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $filteredCategories,
                ]
            )
            ->add(
                'images', 
                CollectionType::class, 
                [
                    'error_bubbling' => false,
                    'entry_type' => ImageType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'videos', 
                CollectionType::class, 
                [
                    'error_bubbling' => false,
                    'entry_type' => VideoType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'attr' => [
                'novalidate' => 'novalidate', // Comment me to reactivate the html5 validation!  🚥
            ],
            'filtered_Artists' => null,
            'filtered_Categories' => null,
        ]);
    }
}