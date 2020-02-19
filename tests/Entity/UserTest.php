<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Interfaces\EntityTestInterface;
use App\Tests\TestHelperTrait;
use Faker\Factory;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase implements EntityTestInterface
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

    public function getEntity(): User
    {
        $faker = Factory::create('fr_FR');

        $user = new User();;
        $user->setUsername($faker->words(1, true))
            ->setPassword($faker->password)
            ->setEmail($faker->email);
        ;

        return $user;
    }

    public function testUniqEntityValidation(): void
    {
        $user = $this->fixtures['user_user'];

        $properties = [
            'Username' => $user->getUsername(),
            'Email' => $user->getEmail()
        ];
        $this->uniqEntityValidation($properties);
    }

    public function testNotBlankProperties(): void
    {
        $properties = [
            'Username',
            'Email',
            'Password'
        ];
        $this->notBlankProperties($properties);
    }
    /**
     * @dataProvider getFalseEmail
     * @param string $email
     */
    public function testEmailValidation($message, $email, $expected): void
    {
        $properties = [
            'Email'
        ];

        $this->emailValidation($properties, $email, $expected);
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