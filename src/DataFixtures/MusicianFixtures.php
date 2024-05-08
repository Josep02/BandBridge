<?php

namespace App\DataFixtures;

use App\Entity\Musician;
use App\Entity\Instrument;
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
        $instruments = $manager->getRepository(Instrument::class)->findAll();

        $randomInstrument = $this->faker->randomElement($instruments);

        $admin = new Musician();
        $admin->setName('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setImage('admin.jpg');
        $admin->setInstrument($randomInstrument);
        $admin->setLastname('admin');
        $admin->setPassword('admin');
        $admin->setUsername('admin');

        $manager->persist($admin);

        // Insertar músicos
        for ($i = 0; $i < 10; $i++) {
            $musician = new Musician();
            $musician->setName($this->faker->name);
            $musician->setLastname($this->faker->lastName);
            $musician->setPassword($this->faker->password);
            $musician->setEmail($this->faker->email);
            $musician->setUsername($this->faker->userName);
            $musician->setImage('image.jpg');

            $randomInstrument = $this->faker->randomElement($instruments);
            $musician->setInstrument($randomInstrument);

            $manager->persist($musician);
        }

        $manager->flush();
    }
}