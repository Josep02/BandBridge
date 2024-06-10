<?php

namespace App\DataFixtures;

use App\Entity\Details;
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
        $details = $manager->getRepository(Details::class)->findAll();

        $states = ['In process', 'Refused', 'Accepted'];

        foreach ($details as $detail) {
            $numParticipationRequests = rand(0, 20);
            for ($i = 0; $i < $numParticipationRequests; $i++) {
                $participation = new ParticipationRequest();

                $randomState = $this->faker->randomElement($states);
                $participation->setState($randomState);

                $randomEvent = $detail->getEvent();
                $participation->setEvent($randomEvent);

                $randomMusician = $this->faker->randomElement($musicians);
                $participation->setMusician($randomMusician);

                $participation->setDetail($detail);

                $participation->setApplicationDate($this->faker->dateTime);

                $manager->persist($participation);
            }
        }

        $manager->flush();
    }
}