<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationType extends AbstractType implements FormTypeInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datecheckin',DateTimeType::class,[
                'date_widget' => 'single_text',
                'label' => 'Date de début',
            ])
            ->add('datecheckout', DateTimeType::class,[
                'date_widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('all_day', null, [
                'label' => 'Journée entière ?',
                'data' => false,
            ])
            ->add('background_color', ColorType::class,[
                'label' => 'Couleur de fond',
            ])
            ->add('border_color', ColorType::class, [
                'label' => 'Couleur de bordure',
            ])
            ->add('text_color', ColorType::class, [
                'label' => 'Couleur du texte',
            ])
            ->add('status', null, [
                'label' => 'Statut',
                'data' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
