<?php

namespace App\DataFixtures;

use App\Entity\Musician;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class MusicianFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $musicians = [];

        for ($i = 0 ; $i < 10 ; $i++) {
            $musician = new Musician();
            $musician->setName($this->faker->name);
            $musician->setLastname($this->faker->lastName);
            $musician->setPassword($this->faker->password);
            $musician->setEmail($this->faker->email);
            $musician->setUsername($this->faker->userName);
            $musician->setImage('image.jpg');

            $musicians[] = $musician;
            $manager->persist($musician);
        }

        $manager->flush();
    }
}