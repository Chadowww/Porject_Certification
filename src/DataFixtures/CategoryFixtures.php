<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
CONST CATEGORIES = [
    "Fiction générale",
    "Romans",
    "Science-fiction",
    "Fantasy",
    "Mystère/Thriller",
    "Policier",
    "Horreur",
    "Dystopie",
    "Aventure",
    "Romance",
    "Jeunesse/Young Adult",
    "Classiques de la littérature",
    "Poésie",
    "Théâtre",
    "Nouvelles",
    "Biographies et mémoires",
    "Essais",
    "Histoire",
    "Philosophie",
    "Science et technologie",
    "Arts et musique",
    "Voyages et aventures",
    "Livres illustrés",
    "Livres pour enfants"
];

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        foreach (self::CATEGORIES as $i => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $i, $category);
            $manager->persist($category);

        }

        $manager->flush();
    }
}
