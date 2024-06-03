<?php

namespace App\Controller;

use App\Repository\DetailsRepository;
use App\Repository\EventRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipationRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back')]
class BackController extends AbstractController
{
    #[Route('/', name: 'app_back')]
    public function index(EventRepository $eventRepository, MusicianRepository $musicianRepository,ParticipationRequestRepository $participationRequestRepository, InstrumentRepository $instrumentRepository, DetailsRepository $detailsRepository): Response
    {
        $eventsCount = $eventRepository->count([]);
        $participationCount = $participationRequestRepository->count([]);
        $musiciansCount = $musicianRepository->count([]);

        $events = $eventRepository->findBy([], ['date' => 'ASC']);

        $details = $detailsRepository->findAll();

        $instrumentCount = [];
        foreach ($details as $detail) {
            $instrumentName = $detail->getRequiredInstrument()->getName();
            if (!isset($instrumentCount[$instrumentName])) {
                $instrumentCount[$instrumentName] = 0;
            }
            $instrumentCount[$instrumentName] += 1;
        }

        arsort($instrumentCount);
        $mostRequestedInstrument = key($instrumentCount);
        $mostRequestedInstrumentCount = reset($instrumentCount);

        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
            'events_count' => $eventsCount,
            'participation_count' => $participationCount,
            'most_requested_instrument' => $mostRequestedInstrument,
            'most_requested_instrument_count' => $mostRequestedInstrumentCount,
            'musicians_count' => $musiciansCount,
            'events' => $events,
        ]);
    }
}
