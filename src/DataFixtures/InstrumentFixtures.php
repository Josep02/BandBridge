<?php

namespace App\DataFixtures;

use App\Entity\Classification;
use App\Entity\Instrument;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstrumentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $classification1 = $this->createClassification($manager, 'Viento Madera');
        $classification2 = $this->createClassification($manager, 'Viento Metal');
        $classification3 = $this->createClassification($manager, 'Cuerda');
        $classification4 = $this->createClassification($manager, 'Percusión');

        $this->createInstruments($manager, $classification1, [
            'Flauta',
            'Clarinete',
            'Oboe',
            'Fagot',
            'Saxofón',
            'Saxofón Tenor',
        ]);

        $this->createInstruments($manager, $classification2, [
            'Trompeta',
            'Trombón',
            'Tuba',
            'Corneta',
            'Trompa',
        ]);

        $this->createInstruments($manager, $classification3, [
            'Violín',
            'Viola',
            'Violonchelo',
            'Contrabajo',
            'Guitarra',
            'Banjo',
            'Arpa',
            'Piano',
        ]);

        $this->createInstruments($manager, $classification4, [
            'Batería',
            'Pandereta',
            'Caja',
            'Timbales',
            'Congas',
            'Bongos',
            'Xilófono',
            'Platos',
        ]);

        $manager->flush();
    }

    private function createClassification(ObjectManager $manager, string $name): Classification
    {
        $classification = new Classification();
        $classification->setName($name);
        $manager->persist($classification);

        return $classification;
    }

    private function createInstruments(ObjectManager $manager, Classification $classification, array $instrumentNames): void
    {
        foreach ($instrumentNames as $name) {
            $instrument = new Instrument();
            $instrument->setName($name);
            $instrument->setClassification($classification);
            $manager->persist($instrument);
        }
    }
}