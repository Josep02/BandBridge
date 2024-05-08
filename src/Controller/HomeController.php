<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        $activeEvents = $eventRepository->findBy(['state' => 'Active']);
        $closedEvents = $eventRepository->findBy(['state' => 'Closed']);

        return $this->render('home/index.html.twig', [
            'activeEvents' => $activeEvents,
            'closedEvents' => $closedEvents,
        ]);
    }
}
