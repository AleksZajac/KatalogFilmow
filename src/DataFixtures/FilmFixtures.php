<?php
/**
 * Film fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Films;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
/*
 *  Class FilmFixtures
 */
class FilmFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $film = new Films();
            $film->setTitle($this->faker->sentence);
            $film->setDescription($this->faker->text);
            $film->setReleaseDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $this->manager->persist($film);
        }

        $manager->flush();

    }
}
