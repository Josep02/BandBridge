<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use App\Entity\OrganizationType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganizationFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        // Crear los tipos de organización
        $organizationTypes = [
            ['name' => 'Banda Musical', 'description' => 'Una banda musical que toca diferentes estilos.'],
            ['name' => 'Charanga', 'description' => 'Grupo musical típico de España con repertorio festivo.'],
            ['name' => 'Jazz Band', 'description' => 'Conjunto de músicos que tocan música jazz.'],
            ['name' => 'Brass Band', 'description' => 'Agrupación musical con instrumentos de viento metal y percusión.'],
            ['name' => 'Orquesta', 'description' => 'Conjunto musical que interpreta música clásica y contemporánea.'],
        ];

        foreach ($organizationTypes as $organizationTypeData) {
            $organizationType = new OrganizationType();
            $organizationType->setName($organizationTypeData['name']);
            $organizationType->setDescription($organizationTypeData['description']);
            $manager->persist($organizationType);
        }

        $organizations = [];

        for ($i = 0 ; $i < 10 ; $i++) {
            $organization = new Organization();
            $organization->setName('Agrupacio Musical de Pego');
            $organization->setDescription('adasdas');
            $organization->setEmail('asas@gmail.com');

            $organizations[] = $organization;
            $manager->persist($organization);
        }

        $manager->flush();
    }
}