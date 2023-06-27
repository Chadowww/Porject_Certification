<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $comment = new Comment();

            $comment
                ->setUser($this->getReference('user_' . random_int(0, 4)))
                ->setBook($this->getReference('book_' . random_int(0, 49)))
                ->setComment($faker->text)
                ->setNote($faker->numberBetween(0, 5));

            $manager->persist($comment);
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
