<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Musician;
use App\Entity\ParticipationRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ParticipationFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $musicians = $manager->getRepository(Musician::class)->findAll();
        $events = $manager->getRepository(Event::class)->findAll();

        $states = ['In process', 'Refused', 'Accepted'];

        for ($i = 0; $i < 50; $i++) {
            $participation = new ParticipationRequest();

            $randomState = $this->faker->randomElement($states);
            $participation->setState($randomState);

            $randomEvent = $this->faker->randomElement($events);
            $participation->setEvent($randomEvent);

            $randomMusician = $this->faker->randomElement($musicians);
            $participation->setMusician($randomMusician);

            $participation->setApplicationDate($this->faker->dateTime);

            $manager->persist($participation);
        }

        $manager->flush();
    }
}