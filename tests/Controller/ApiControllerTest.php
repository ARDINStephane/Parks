<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    private $client;
    private $fixtures;

    protected function setUp() {
        $this->client = static::createClient();
        parent::setup();
        $this->fixtures = $this->loadFixtureFiles([
            __DIR__ . '/fixtures.yaml',
        ]);
    }

    public function testIndex()
    {
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testApiDoc()
    {
        $this->client->request('GET', '/api');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageAdminIsRestricted()
    {
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testFullApiIsRestricted()
    {

        //Création d'un booking
        $booking = $this->fixtures['booking_1'];
        //Création d'un parking
        $parking = $this->fixtures['parking_1'];
        $routes = [
            "bookings/",
            "bookings/" . $booking->getId(),
            "bookings/" . $booking->getId() . "/parking",
            "bookings/" . $booking->getId() . "/parking/bookings",
            "parkings/" . $parking->getId() . "/bookings",
            "parkings/complete",
            "parkings/simple",
            "parkings/" . $booking->getId() . "/complete",
            "parkings/" . $booking->getId() . "/simple",

        ];
        foreach($routes as $route) {
            $this->client->request('GET', '/api/' . $route);
            $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        }
}

    /**
     * @dataProvider getUsersFixtureType
     * @param string $userFixtureType
     */
    public function testLoginAdmin(string $userFixtureType)
    {
        $this->login($this->client, $this->createUser($userFixtureType));
        $this->client->request('GET', '/admin');
        if($userFixtureType == 'user_admin') {
            $this->assertResponseStatusCodeSame(301);
        } else {
            $this->assertResponseStatusCodeSame(403);
        }
    }

    /**
     * @dataProvider getUsersFixtureType
     * @param string $userFixtureType
     */
    public function testGetAllBookings(string $userFixtureType)
    {
        //Création du token utilisateur
        $token = $this->createUserToken($userFixtureType);

        //On appelle la route
        $this->client->request('GET', '/api/bookings'
            , array() , array()
            , array('HTTP_AUTHORIZATION' => 'Bearer '. $token)
        );

        //On vérifie que le code de retour est bien 200
        $this->assertEquals(200 , $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getUsersFixtureType
     * @param string $userFixtureType
     */
    public function testGetBooking(string $userFixtureType)
    {
        //Création du token utilisateur
        $token = $this->createUserToken($userFixtureType);
        //Création d'un booking
        $booking = $this->fixtures['booking_1'];

        //On appelle la route
        $this->client->request('GET', '/api/bookings/' . $booking->getId(),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '. $token]
        );

        //On vérifie que le code de retour est bien 200
        $response = $this->client->getResponse();
        $this->assertEquals(200 , $response->getStatusCode());
    }

    /**
     * @dataProvider getUsersFixtureType
     * @param string $userFixtureType
     */
    public function testPostBookings(string $userFixtureType)
    {
        //Création du token utilisateur
        $token = $this->createUserToken($userFixtureType);
        //Création d'un booking
        $bookingId = $this->fixtures['parking_1']->getId();

        //On appelle la route
        $crawler = $this->client->request('POST', '/api/bookings'
            , []
            , []
            , [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '. $token
            ],
            '{}'
        );

        if($userFixtureType == 'user_admin' || $userFixtureType == 'user_user') {
            $this->assertResponseStatusCodeSame(200);
        } else {
            $this->assertResponseStatusCodeSame(403);
        }
    }

    /**
     * @dataProvider getBookingsForTests
     * @param string $userFixtureType
     */
    public function testPutBooking(string $userFixtureType)
    {
        //Création du token utilisateur
        $token = $this->createUserToken($userFixtureType);
        //Création d'un booking
        $bookingId = $this->fixtures['parking_1']->getId();

        //On appelle la route
        $this->client->request('PUT', '/api/bookings/' . $bookingId
            , []
            , []
            , [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '. $token
            ],
            '{}'
        );

        if($userFixtureType == 'user_admin' || $userFixtureType == 'user_user') {
            $this->assertResponseStatusCodeSame(200);
        } else {
            $this->assertResponseStatusCodeSame(403);
        }
    }

    public function testPOSTCreateToken()
    {
        $user = $this->createUser('user_admin');
        $this->client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username":"'.$user->getUsername().'","password":"'.$user->getUsername().'"}'
        );
        $this->assertResponseStatusCodeSame(200);
    }

    private function createUser(string $userFixtureType)
    {
        return $this->fixtures[$userFixtureType];
    }

    public function createUserToken(string $userFixtureType)
    {
        $user = $this->createUser($userFixtureType);

        return $this->client->getContainer()
            ->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);
    }

    public function getUsersFixtureType(): array
    {
        return [
            ['user_user'],
            ['user_admin'],
            ['user_other'],
        ];
    }
}
