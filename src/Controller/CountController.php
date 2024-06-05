<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CountController extends AbstractController
{
    #[Route('/count', name: 'app_count')]
    public function index(): Response
    {
        return $this->render('count/index.html.twig', [
            'controller_name' => 'CountController',
        ]);
    }
}
