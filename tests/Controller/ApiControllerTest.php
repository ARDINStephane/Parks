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

    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testApiDoc()
    {
        $client = static::createClient();

        $client->request('GET', '/api');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testPageAdminIsRestricted()
    {
        $client = static::createClient();

        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testFullApiIsRestricted()
    {
        $client = static::createClient();

        $client->request('POST', '/api/bookings');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testLoginAdmin()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_admin']);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(301);
    }
}
