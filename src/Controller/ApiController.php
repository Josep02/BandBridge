<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/count', name: 'app_api_count')]
    public function index(InvitationRepository $invitationRepository): Response
    {
        $user = $this->getUser();
        $totalInvitations = 0;

        if ($user instanceof UserInterface) {
            $invitations = $invitationRepository->findBy(['state' => 'Pendiente', 'musician' => $user]);
            $totalInvitations = count($invitations);
        }

        return $this->json(['count_number' => $totalInvitations]);
    }

    #[Route('/chart', name: 'app_api_chart')]
    public function chart(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        $eventsByMonth = [];
        foreach ($events as $event) {
            $date = $event->getCreated();
            $month = $date->format('Y-m');
            if (!isset($eventsByMonth[$month])) {
                $eventsByMonth[$month] = 0;
            }
            $eventsByMonth[$month]++;
        }

        ksort($eventsByMonth);

        $labels = array_keys($eventsByMonth);
        $data = array_values($eventsByMonth);

        return $this->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}