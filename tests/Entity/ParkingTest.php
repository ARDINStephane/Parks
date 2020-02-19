<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Parking;
use App\Tests\Interfaces\EntityTestInterface;
use App\Tests\TestHelperTrait;
use Faker\Factory;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParkingTest extends KernelTestCase implements EntityTestInterface
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
    public function getEntity(): Parking
    {
        $faker = Factory::create('fr_FR');

        $parking = new Parking();
        $parking->setNom($faker->words(4, true))
            ->setAdresse($faker->address)
            ->setCodePostal($faker->postcode)
            ->setVille($faker->city)
            ->setPays($faker->country)
            ->setLatidude($faker->latitude)
            ->setLongitude($faker->longitude);
        $booking = new Booking;
        $booking->setDateDebut($faker->dateTimeBetween('-6 months'))
            ->setDateFin($faker->dateTimeBetween('now','6 months'))
            ->setUtilisateurEmail($faker->email)
            ->setNumero(2)
            ->setParking($parking);
        $parking->addBooking($booking);

        return $parking;
    }

    public function testValidEntity():void
    {
        $parking = $this->getEntity();

        $this->validateEntity($parking);
    }

    public function testUniqEntityValidation(): void
    {
        $parking = $this->fixtures['parking_2'];

        $properties = [
            'Nom' => $parking->getNom()
        ];
        $this->uniqEntityValidation($properties);
    }

    public function testNotBlankProperties(): void
    {
        $properties = [
            'Nom',
            'CodePostal',
            'Pays',
            'Longitude',
            'Ville',
        ];
        $this->notBlankProperties($properties);
    }

    public function testNotBlankRelationships(): void
    {
        $properties = [
            'Bookings'
        ];
        $this->notBlankRelationships($properties);
    }

    public function testPropertiesLengthValidation(): void
    {
        $properties = [
            'Pays' => [
                "min" => 2,
                "max" => 100
            ]
        ];
        $this->PropertiesLengthValidation($properties);
    }

    /**
     * @dataProvider getFalsePostalCode
     * @param string $postalCode
     */
    public function testPropertiesRegexValidation($message, $postalCode, $expected): void
    {
        $properties = [
            'CodePostal'
        ];
        $this->PropertiesCodePostalValidation($properties, $postalCode, $expected);
    }

    public function getFalsePostalCode(): array
    {
        return [
            [
                "[getFalsePostalCode] ==> success: good Postal Code",
                '91660',
                0
            ],
            [
                "[getFalsePostalCode] ==> success: good Postal Code",
                '91 218',
                0
            ],
            [
                "[getFalsePostalCode] ==> Bad Postal Code",
                '99 9999',
                1
            ],
            [
                "[getFalsePostalCode] ==> Bad Postal Code",
                '1',
                1
            ],
            [
                "[getFalsePostalCode] ==> Bad Postal Code",
                '1µ*op',
                1
            ],
            [
                "[getFalsePostalCode] ==> Bad Postal Code",
                '91 90*',
                1
            ]
        ];
    }
}