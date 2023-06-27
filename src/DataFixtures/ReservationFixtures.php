<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();

            $reservation
                ->setBook($this->getReference('book_' . $i))
                ->setUser($this->getReference('user_' . random_int(0, 4)))
                ->setDatecheckout($faker->dateTimeBetween('-1 week', '+1 week'))
                ->setDatecheckin($faker->dateTimeBetween('-1 week', '+1 week'))
                ->setStatus($faker->boolean());


            $manager->persist($reservation);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookFixtures::class,
            UserFixtures::class,
        ];
    }
}
