<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use function Sodium\add;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Votre recherche',
                'attr' => [
                    'placeholder' => 'Que cherchez-vous ?',
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class,[
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Les categories',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('author', EntityType::class,[
                'class' => 'App\Entity\Author',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Les auteurs',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('editor', EntityType::class,[
                'class' => 'App\Entity\Editor',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Les éditeurs',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ->add('submit', SubmitType::class,[
            'label' => 'Filter',
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}