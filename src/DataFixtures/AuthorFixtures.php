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
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);

        for ($i = 0; $i < 50; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $author->setAvatar($faker->imageUrl(300, 300, 'people'));
            $this->addReference('author_' . $i, $author);
            $manager->persist($author);

        }

        $manager->flush();
    }
}
