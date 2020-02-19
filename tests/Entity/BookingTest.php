<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Parking;
use App\Tests\Interfaces\EntityTestInterface;
use App\Tests\TestHelperTrait;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Faker\Factory;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingTest extends KernelTestCase implements EntityTestInterface
{
    use FixturesTrait;
    use TestHelperTrait;

    private $fixtures;

    protected function setUp(): void
    {
        $this->fixtures = $this->loadFixtureFiles([
            __DIR__ . '/../fixtures.yaml',
        ]);
    }

    public function testValidEntity(): void
    {
        $booking = $this->getEntity();

        $this->validateEntity($booking);
    }

    public function getEntity(): Booking
    {
        $faker = Factory::create('fr_FR');

        $booking = new Booking;
        $booking->setDateDebut($faker->dateTimeBetween('-6 months'))
            ->setDateFin($faker->dateTimeBetween('now','6 months'))
            ->setUtilisateurEmail($faker->email)
            ->setNumero(2);
        $parking = new Parking();
        $parking->setNom($faker->words(4, true))
            ->setAdresse($faker->address)
            ->setCodePostal($faker->postcode)
            ->setVille($faker->city)
            ->setPays($faker->country)
            ->setLatidude($faker->latitude)
            ->setLongitude($faker->longitude)
            ->addBooking($booking);
        $booking->setParking($parking);

        return $booking;
    }

    public function testUniqEntityValidation(): void
    {
        $parking = $this->fixtures['parking_2'];

        $properties = [
            'Parking' => $parking,
            'Numero' => '10002'
        ];
        $this->uniqEntityValidation($properties);
    }

    public function testNotBlankProperties(): void
    {
        $properties = [
            'UtilisateurEmail',
            'Numero'
        ];
        $this->notBlankProperties($properties);
    }

    public function testNotBlankRelationship(): void
    {
        $relationship = [
            'Parking',
        ];
        $this->notBlankRelationshipsSideOne($relationship);
    }

    /**
     * @dataProvider getFalseDate
     * @param $date1
     * @param $date2
     */
/*    public function testDateValidation($date1, $date2): void
    {
        //Création d'un booking
        $booking = $this->getEntity();
        $booking->setDateFin($date2);
        $booking->setDateDebut($date1);

        $this->assertHasErrors($booking, 1);
    }*/
    /**
     * @dataProvider getFalseEmail
     * @param string $email
     */
    public function testEmailValidation($message, $email, $expected): void
    {
        $properties = [
            'UtilisateurEmail'
        ];

        $this->emailValidation($properties, $email, $expected);
    }

    public function getFalseDate(): array
    {
        $date1 = new \DateTime('now');
        $date1->modify('-1 year');
        $date2 = new \DateTime('now');
        $date2->modify("+1 year");

        return [
            [
                $date1,
                $date2,
            ],
            [
                $date2,
                $date1,
            ]
        ];
    }

    public function getFalseEmail(): array
    {
        //0 success, 5 errors
        return [
            [
                "[getFalseEmail] ==> success: good Email",
                'toto@toto.fr',
                0
            ],
            [
                "[getFalseEmail] ==> success: good Email",
                'ppppp',
                1
            ],
            [
                "[getFalseEmail] ==> success: good Email",
                '99 9999.fr',
                1
            ],
            [
                "[getFalseEmail] ==> success: good Email",
                '00@00;fr',
                1
            ],
            [
                "[getFalseEmail] ==> success: good Email",
                '@.',
                1
            ],
            [
                "[getFalseEmail] ==> success: good Email",
                'totototo.fr',
                1
            ],
        ];
    }
}