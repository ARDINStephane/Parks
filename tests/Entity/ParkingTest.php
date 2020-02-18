<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Parking;
use App\Tests\TestHelperTrait;
use Faker\Factory;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParkingTest extends KernelTestCase
{
    use FixturesTrait;
    use TestHelperTrait;

    private $fixtures;


    protected function setUp() {
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
        $parking->addBooking($booking);

        return $parking;
    }

    public function testValidEntity()
    {
        $parking = $this->getEntity();

        $this->assertHasErrors($parking, 0);
    }

    public function testUniqEntityValidation()
    {
        $properties = [
            'Nom' => 'toto'
        ];
        $this->uniqEntityValidation($properties);
    }

    public function testNotBlankProperties()
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

    public function testNotBlankRelationships()
    {
        $properties = [
            'Bookings'
        ];
        $this->notBlankRelationships($properties);
    }

    public function testPropertiesLengthValidation()
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
    public function testPropertiesRegexValidation($postalCode)
    {
        $properties = [
            'CodePostal'
        ];
        $this->PropertiesCodePostalValidation($properties, $postalCode);
    }

    public function getFalsePostalCode(): array
    {
        return [
            ['ppppp'],
            ['99 9999'],
            ['1'],
            ['1µ*op'],
            ['91 90*'],
        ];
    }
}