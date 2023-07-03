<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(AuthorRepository $authorRepository, CategoryRepository $categoryRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);

        for ($i = 0; $i < 50; $i++) {
            $book = new Book();
            $book->setTitle($faker->words($nb = 3, $asText = true));
            $book->setDescription($faker->text);
            $book->setCover($faker->imageUrl(300, 300, 'technics'));
            $book->setPublish($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null));
            $book->setQteStock($faker->numberBetween(1, 10));
            $book->setQteCheckout($faker->numberBetween(1, 10));
            $book->addEditor($this->getReference('editor_' . $faker->numberBetween(0, 49)));
            $book->addAuthor($this->getReference('author_' . $faker->numberBetween(0, 49)));
            for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                $book->addCategory($this->getReference('category_' . $faker->numberBetween(0, 9)));
            }
            $this->addReference('book_' . $i, $book);
            $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuthorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
