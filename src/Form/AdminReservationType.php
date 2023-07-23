<?php

namespace App\Form;

use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminReservationType extends AbstractType implements FormTypeInterface
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'email',
                'label' => 'Utilisateur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC');
                },
            ])
            ->add('book', EntityType::class, [
                'class' => 'App\Entity\Book',
                'choice_label' => 'title',
                'label' => 'Livre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.title', 'ASC');
                },
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Statut',
                'required' => false,
            ])
            ->add('datecheckin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
            ])
            ->add('datecheckout', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('all_day', CheckboxType::class, [
                'label' => 'Journée entière',
                'required' => false,
            ])
            ->add('background_color', ColorType::class, [
                'label' => 'Couleur de fond',
                'required' => false,
            ])
            ->add('border_color', ColorType::class, [
                'label' => 'Couleur de bordure',
                'required' => false,
            ])
            ->add('text_color', ColorType::class, [
                'label' => 'Couleur du texte',
                'required' => false,
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