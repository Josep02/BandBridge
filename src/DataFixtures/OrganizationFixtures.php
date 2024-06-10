<?php

namespace App\DataFixtures;

use App\Entity\Details;
use App\Entity\Event;
use App\Entity\Instrument;
use App\Entity\Musician;
use App\Entity\MusicianClass;
use App\Entity\Organization;
use App\Entity\OrganizationType;
use App\Entity\ParticipationRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class OrganizationFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $organizationTypes = [
            ['name' => 'Banda Musical', 'description' => 'Una banda musical que toca diferentes estilos.'],
            ['name' => 'Charanga', 'description' => 'Grupo musical típico de España con repertorio festivo.'],
            ['name' => 'Jazz Band', 'description' => 'Conjunto de músicos que tocan música jazz.'],
            ['name' => 'Brass Band', 'description' => 'Agrupación musical con instrumentos de viento metal y percusión.'],
            ['name' => 'Orquesta', 'description' => 'Conjunto musical que interpreta música clásica y contemporánea.'],
        ];

        $musicians = $manager->getRepository(Musician::class)->findAll();
        $events = $manager->getRepository(Event::class)->findAll();
        $details = $manager->getRepository(Details::class)->findAll();

        $states = ['In process', 'Refused', 'Accepted'];

        $organizationTypeEntities = [];

        foreach ($organizationTypes as $organizationTypeData) {
            $organizationType = new OrganizationType();
            $organizationType->setName($organizationTypeData['name']);
            $organizationType->setDescription($organizationTypeData['description']);
            $manager->persist($organizationType);
            $organizationTypeEntities[] = $organizationType;
        }

        for ($i = 0; $i < 10; $i++) {
            $organization = new Organization();

            $randomType = $organizationTypeEntities[array_rand($organizationTypeEntities)];
            $organization->setOrganizationType($randomType);

            $organization->setName($this->faker->company);
            $organization->setDescription($this->faker->text(255));
            $organization->setEmail($this->faker->email);
            $organization->setImage('card.png');
            $manager->persist($organization);
            $organizations[] = $organization;
        }

        $musicians = $manager->getRepository(Musician::class)->findAll();
        $instruments = $manager->getRepository(Instrument::class)->findAll();

        for ($i = 0; $i < 100; $i++) {
            $musicianClass = new MusicianClass();

            $randomMusician = $this->faker->randomElement($musicians);
            $musicianClass->setMusician($randomMusician);

            $randomOrganization = $organizations[array_rand($organizations)];
            $musicianClass->setOrganization($randomOrganization);

            $role = $this->faker->randomElement(['Organizer', 'Musician']);
            $musicianClass->setRole($role);

            $manager->persist($musicianClass);
        }

        for ($i = 0; $i < 20; $i++) {
            $event = new Event();

            $randomOrganization = $organizations[array_rand($organizations)];
            $event->setOrganization($randomOrganization);

            $event->setName($this->faker->company);
            $event->setDescription($this->faker->text(255));
            $event->setDate($this->faker->dateTime);
            $event->setCreated($this->faker->dateTime);
            $event->setLocation('Pego');

            $randomState = $this->faker->randomElement(['Active', 'Closed']);
            $event->setState($randomState);

            $manager->persist($event);

            for ($j = 0; $j < 5; $j++) {
                $details = new Details();

                $details->setQuantity(rand(1, 5));
                $details->setMinPayment(35);
                $details->setEvent($event);

                $randomInstrument = $instruments[array_rand($instruments)];
                $details->setRequiredInstrument($randomInstrument);

                $manager->persist($details);
            }
        }

        $manager->flush();
    }
}