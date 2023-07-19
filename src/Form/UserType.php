<?php

namespace App\Form;

use App\Entity\User;
use Faker\Provider\PhoneNumber;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veillez entrer un email',
                    ]),
                    new Assert\Email([
                        'message' => 'L\'email n\'est pas valide',
                    ]),
                ],
            ])
//            ->add('roles')
//            ->add('password')
            ->add('firstname', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veillez entrer un prénom',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veillez entrer un nom',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^((\+)33|0)[1-9](\d{2}){4}$/',
                    ]),
                ],
            ])
            ->add('adress', TextType::class, [
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'required' => false,
            ])
            ->add('code', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Le code postal n\'est pas valide',
                    ]),
                ],
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Country([
                        'message' => 'Le pays n\'est pas valide',
                    ]),
                ],
            ])
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
