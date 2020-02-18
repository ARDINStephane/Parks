<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingTest extends KernelTestCase
{
   /* public function testValidEntity()
    {
        $faker = Factory::create('fr_FR');

        $booking = new Booking;
        $booking->setDateDebut($faker->dateTimeBetween('-6 months'))
            ->setDateFin($faker->dateTimeBetween('now','6 months'))
            ->setUtilisateurEmail($faker->email)
            ->setNumero(2);

        self::bootKernel();

        $error = self::$container->get('validator')->validate($booking);
        $this->assertCount(0, $error);
    }*/
}