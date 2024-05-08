<?php

namespace App\DataFixtures;

use App\Entity\Musician;
use App\Entity\Valoration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ValorationFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $musicians = $manager->getRepository(Musician::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $valoration = new Valoration();
            $valoration->setTitle($this->faker->title);
            $valoration->setDescription($this->faker->text(255));

            $randomMusician = $this->faker->randomElement($musicians);
            $valoration->setMusician($randomMusician);

            $manager->persist($valoration);
        }

        $manager->flush();
    }
}