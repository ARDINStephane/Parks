<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use App\Repository\ParkingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $test = 'yess';

        return $this->render('pages/test.html.twig', [
            'test' => $test
        ]);
    }
}
