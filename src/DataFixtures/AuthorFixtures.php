<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $manager->persist($author);

        }

        $manager->flush();
    }
}
