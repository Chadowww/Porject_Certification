<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $this->addReference('category_', $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
