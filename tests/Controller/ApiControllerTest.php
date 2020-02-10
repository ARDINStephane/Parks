<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}