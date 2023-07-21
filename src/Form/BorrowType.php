<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
