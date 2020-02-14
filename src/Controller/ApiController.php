<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\ParkingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $manager)
    {
        $test = 'yess';

        return $this->render('pages/test.html.twig', [
            'test' => $test
        ]);
    }
}
