<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $test = 'yes';
        return $this->render('pages/test.html.twig', [
            'test' => $test
        ]);
    }
}
