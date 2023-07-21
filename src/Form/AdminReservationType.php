<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;

class AdminReservationType extends AbstractType implements FormTypeInterface
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user')
            ->add('book')
            ->add('status')
            ->add('datecheckin')
            ->add('datecheckout')
            ->add('description')
            ->add('all_day')
            ->add('background_color')
            ->add('border_color')
            ->add('text_color')
        ;
    }
}