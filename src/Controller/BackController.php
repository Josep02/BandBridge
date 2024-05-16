<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\MusicianRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back')]
class BackController extends AbstractController
{
    #[Route('/', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }

    #[Route('/events', name: 'app_back_events')]
    public function events(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        $activeEvents = $eventRepository->findBy(['state' => 'Active']);
        $closedEvents = $eventRepository->findBy(['state' => 'Closed']);

        return $this->render('back/back_events.html.twig', [
            'events' => $events,
            'activeEvents' => $activeEvents,
            'closedEvents' => $closedEvents,
        ]);
    }

    #[Route('/users', name: 'app_back_users')]
    public function users(MusicianRepository $musicianRepository): Response
    {
        $users = $musicianRepository->findAll();

        return $this->render('back/back_users.html.twig', [
            'users' => $users
        ]);
    }
}