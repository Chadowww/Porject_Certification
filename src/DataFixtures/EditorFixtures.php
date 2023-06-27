<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EditorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $editor = new Editor();
            $editor->setName($faker->company);
            $this->addReference('editor_' . $i, $editor);
            $manager->persist($editor);
        }
        $manager->flush();
    }
}
