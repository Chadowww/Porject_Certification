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
	private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'id',
                'data' => $user,
            ])
            ->add('status')
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'title',
                'label' => 'Livre',
                'placeholder' => 'Choisir un livre',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('datecheckin',DateTimeType::class,[
                'date_widget' => 'single_text',
            ])
            ->add('datecheckout', DateTimeType::class,[
                'date_widget' => 'single_text',
            ])
            ->add('description')
            ->add('all_day')
            ->add('background_color', ColorType::class)
            ->add('border_color', ColorType::class)
            ->add('text_color', ColorType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
