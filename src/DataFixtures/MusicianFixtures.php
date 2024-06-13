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
        $this->faker = Factory::create('es_ES');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $instruments = $manager->getRepository(Instrument::class)->findAll();

        // Crear un usuario admin
        $admin = new Musician();
        $admin->setName('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setImage('example.png');
        $admin->setLastname('admin');

        // Crear un login para el usuario admin
        $adminLogin = new Login();
        $adminLogin->setUsername('admin');
        $adminLogin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $adminLogin->setRole('ROLE_ADMIN');
        $adminLogin->setMusician($admin);

        $manager->persist($admin);
        $manager->persist($adminLogin);

        // Crear un usuario
        $user = new Musician();
        $user->setName('pepe');
        $user->setEmail('pepe@gmail.com');
        $user->setImage('example.png');
        $user->setLastname('pepe');

        // Crear un login para el usuario
        $userLogin = new Login();
        $userLogin->setUsername('pepe');
        $userLogin->setPassword($this->hasher->hashPassword($user, 'pepe'));
        $userLogin->setRole('ROLE_USER');
        $userLogin->setMusician($user);

        $manager->persist($user);
        $manager->persist($userLogin);

        // Crear usuarios normales
        for ($i = 0; $i < 25; $i++) {
            $musician = new Musician();
            $musician->setName($this->faker->name);
            $musician->setLastname($this->faker->lastName);
            $musician->setEmail($this->faker->email);
            $musician->setImage('example.png');

            $randomInstrument = $this->faker->randomElement($instruments);
            $musician->setInstrument($randomInstrument);

            // Crear un login para el usuario normal
            $normalLogin = new Login();
            $normalLogin->setUsername($this->faker->userName());
            $normalLogin->setPassword($this->hasher->hashPassword($musician, 'password'));
            $normalLogin->setRole('ROLE_USER');
            $normalLogin->setMusician($musician);

            $manager->persist($musician);
            $manager->persist($normalLogin);
        }

        $manager->flush();
    }
}