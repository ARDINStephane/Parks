<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Parking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($p = 0; $p < 5; $p++) {
            $parking = new Parking;
            $parking->setNom($faker->words(3, true))
                ->setAdresse($faker->address)
                ->setCodePostal($faker->postcode)
                ->setVille($faker->city)
                ->setPays($faker->country)
                ->setLatidude($faker->latitude)
                ->setLongitude($faker->longitude);

            for ($c = 0; $c < mt_rand(10, 20); $c++) {
                $booking = new Booking;
                $booking->setDateDebut($faker->dateTimeBetween('-6 months'))
                    ->setDateFin($faker->dateTimeBetween('now','6 months'))
                    ->setUtilisateurEmail($faker->email)
                    ->setParking($parking);

                $manager->persist($booking);
                $parking->addBooking($booking);
            }
            $manager->persist($parking);
        }

        $manager->flush();
    }
}
