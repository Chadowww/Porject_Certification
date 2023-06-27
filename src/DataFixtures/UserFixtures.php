<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setEmail('a.sale@outlook.fr');
        $user->setPassword($this->hasher->hashPassword($user, 'fw7jzpdr7'));
        $user->setFirstname('Alexandre');
        $user->setLastname('Sale');
        $user->setPhone('0606060606');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('chadowow@outlook.fr');
        $user->setPassword($this->hasher->hashPassword($user, 'fw7jzpdr7'));
        $user->setFirstname('Alexandre');
        $user->setLastname('Sale');
        $user->setPhone('0606060606');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $manager->persist($user);


        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail('user' . $i . '@jobitbetter.com');
            $user->setPassword($this->hasher->hashPassword($user, 'user'));
            $user->setRoles(['ROLE_USER']);
            $user->setPhone('0606060606');
            $user->setIsVerified(true);
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
