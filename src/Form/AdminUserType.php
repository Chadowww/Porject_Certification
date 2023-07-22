<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('isVerified')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('adress')
            ->add('code')
            ->add('city')
            ->add('country')
        ;
    }
}