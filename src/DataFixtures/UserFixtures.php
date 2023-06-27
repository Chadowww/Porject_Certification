<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('a.sale@outlook.fr');
        $user->setPassword('fw7jzpdr7');
        $user->setFirstname('Alexandre');
        $user->setLastname('Sale');
        $user->setPhone('0606060606');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('chadowow@outlook.fr');
        $user->setPassword('fw7jzpdr7');
        $user->setFirstname('Alexandre');
        $user->setLastname('Sale');
        $user->setPhone('0606060606');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $manager->persist($user);

        $manager->flush();
    }
}
