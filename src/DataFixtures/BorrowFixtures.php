<?php

namespace App\DataFixtures;

use App\Entity\Borrow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BorrowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $borrow = new Borrow();
            $borrow->setUser($this->getReference('user_' . random_int(0, 4)));
            $borrow->setBook($this->getReference('book_' . random_int(0, 49)));
            $borrow->setCheckin($faker->dateTimeBetween('-3 weeks', '-1 weeks'));
            $borrow->setCheckout($faker->dateTimeBetween('-3 weeks', '-1 weeks'));
            $manager->persist($borrow);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BookFixtures::class,
        ];
    }
}
