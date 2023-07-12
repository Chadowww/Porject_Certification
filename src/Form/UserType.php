<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
//            ->add('roles')
//            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('adress', TextType::class, [
                'required' => false,
            ])
            ->add('city')
            ->add('code')
            ->add('country')
//            ->add('isVerified')
//            ->add('createdAt')
//            ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
