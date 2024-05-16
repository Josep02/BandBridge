<?php

namespace App\DataFixtures;

use App\Entity\Login;
use App\Entity\Musician;
use App\Entity\Instrument;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MusicianFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('es_Es');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $instruments = $manager->getRepository(Instrument::class)->findAll();

        // Crear un usuario admin
        $admin = new Musician();
        $admin->setName('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setImage('admin.jpg');
        $admin->setLastname('admin');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setUsername('admin');

        // Crear un login para el usuario admin
        $adminLogin = new Login();
        $adminLogin->setUsername('admin');
        $adminLogin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $adminLogin->setRole('ROLE_ADMIN');
        $adminLogin->setMusician($admin);

        $manager->persist($admin);
        $manager->persist($adminLogin);

        // Crear usuarios normales
        for ($i = 0; $i < 10; $i++) {
            $musician = new Musician();
            $musician->setName($this->faker->name);
            $musician->setLastname($this->faker->lastName);
            $musician->setPassword($this->hasher->hashPassword($musician, 'password'));
            $musician->setEmail($this->faker->email);
            $musician->setUsername($this->faker->userName);
            $musician->setImage('image.jpg');

            $randomInstrument = $this->faker->randomElement($instruments);
            $musician->setInstrument($randomInstrument);

            // Crear un login para el usuario normal
            $normalLogin = new Login();
            $normalLogin->setUsername($musician->getUsername());
            $normalLogin->setPassword($this->hasher->hashPassword($musician, 'password'));
            $normalLogin->setRole('ROLE_USER');
            $normalLogin->setMusician($musician);

            $manager->persist($musician);
            $manager->persist($normalLogin);
        }

        $manager->flush();
    }
}