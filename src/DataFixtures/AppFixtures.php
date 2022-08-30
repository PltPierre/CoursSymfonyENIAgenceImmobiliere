<?php

namespace App\DataFixtures;

use App\Entity\Acheteur;
use App\Entity\Agent;
use App\Entity\Bien;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        $agents = [];

        for ($i = 0; $i < 10; $i++) {
            $gender = $faker->randomElement(['Autre', 'Monsieur', 'Madame']);
            $agent = new Agent();
            $agent->setNom($faker->lastName());
            $agent->setPrenom($faker->firstName());
            $agent->setCivilite($gender);
            $agent->setPhoto($faker->imageUrl());
            $password = $this->hasher->hashPassword($agent, '1234'.$i);
            $agent->setPassword($password);
            $agent->setEmail('test'.$i.'@test.test');
            $agent->setRoles(['ROLE_AGENT']);
            $agent->setEstSenior($faker->boolean());
            array_push($agents, $agent);
            $manager->persist($agent);
        }

        for ($i = 0; $i < 10; $i++) {
            $acheteur = new Acheteur();
            $acheteur->setNom($faker->lastName());
            $acheteur->setPrenom($faker->firstName());
            $acheteur->setEmail($faker->email());
            $acheteur->setTelephone($faker->e164PhoneNumber());
            $manager->persist($acheteur);
        }


        $gender = $faker->randomElement(['Autre', 'Monsieur', 'Madame']);
        $superagent = new Agent();
        $superagent->setNom($faker->lastName());
        $superagent->setPrenom($faker->firstName());
        $superagent->setCivilite($gender);
        $superagent->setPhoto($faker->imageUrl());
        $password = $this->hasher->hashPassword($superagent, 'test');
        $superagent->setPassword($password);
        $superagent->setEmail('super@super.fr');
        $superagent->setEstSenior($faker->boolean());
        $superagent->setRoles(['ROLE_SUPER_AGENT']);
        $manager->persist($superagent);


        for ($i = 0; $i < 10; $i++) {
            $agentRandom = $faker->randomElement($agents);
            $etat = $faker->randomElement(['excellent', 'rafraichir', 'renovation']);
            $bien = new Bien();
            $bien->setNom($faker->name());
            $bien->setDescription($faker->city());
            $bien->setAvecJardin($faker->boolean());
            $bien->setEtat($etat);
            $bien->setMetresCarres($faker->randomNumber(3));
            $bien->setUrlImg('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1AbcAaG04BKSUiJP6XXssN2ZpVvSUdV4xdQ&usqp=CAU');
            $bien->setAgent($agentRandom);
            $bien->setPrix($bien->getPrixBien());
            $manager->persist($bien);
        }

        $manager->flush();
    }
}
