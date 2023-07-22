<?php

namespace App\Form;

use App\Entity\Borrow;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'email',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC');
                },
            ])
            ->add('book', EntityType::class, [
                'class' => 'App\Entity\Book',
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.title', 'ASC');
                },
            ])
            ->add('checkin', DateTimeType::class, [
                'widget' => 'single_text',
                ])
            ->add('checkout', DateTimeType::class, [
                'widget' => 'single_text',
                ])
//            ->add('user', UserType::class)
//            ->add('book', BookType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
