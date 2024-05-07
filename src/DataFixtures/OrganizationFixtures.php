<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Musician;
use App\Entity\MusicianClass;
use App\Entity\Organization;
use App\Entity\OrganizationType;
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
            $organization->setDescription($this->faker->paragraph);
            $organization->setEmail($this->faker->email);
            $manager->persist($organization);
            $organizations[] = $organization;
        }

        $musicians = $manager->getRepository(Musician::class)->findAll();

        for ($i = 0; $i < 5; $i++) {
            $musicianClass = new MusicianClass();

            $randomMusician = $this->faker->randomElement($musicians);
            $musicianClass->setMusician($randomMusician);

            $randomOrganization = $organizations[array_rand($organizations)];
            $musicianClass->setOrganization($randomOrganization);

            $role = $this->faker->randomElement(['Organizer', 'Musician']);
            $musicianClass->setRole($role);

            $manager->persist($musicianClass);
        }

        for ($i = 0; $i < 10; $i++) {
            $event = new Event();

            $randomOrganization = $organizations[array_rand($organizations)];
            $event->setOrganization($randomOrganization);

            $event->setName($this->faker->title);
            $event->setDescription($this->faker->paragraph);
            $event->setDate($this->faker->dateTime);
            $event->setCreated($this->faker->dateTime);
            $event->setLocation('Pego');
            $event->setState('Active');
            $manager->persist($event);
        }

        $manager->flush();
    }
}