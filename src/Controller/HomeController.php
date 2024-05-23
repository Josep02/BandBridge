<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Musician;
use App\Entity\ParticipationRequest;
use App\Repository\EventRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipationRequestRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        $user = $this->getUser();
        $musician = $user->getMusician();
        $instrument = $musician->getInstrument();

        $forMeEvents = $eventRepository->findForMe($instrument);
        $activeEvents = $eventRepository->findBy(['state' => 'Active']);
        $closedEvents = $eventRepository->findBy(['state' => 'Closed']);

        return $this->render('home/index.html.twig', [
            'forMeEvents' => $forMeEvents,
            'activeEvents' => $activeEvents,
            'closedEvents' => $closedEvents,
        ]);
    }

    #[Route('/home/{id}', name: 'app_home_event_show')]
    public function show(Event $event): Response
    {
        $this->addFlash(
            'warning',
            "Sols els clients poden realitzar compres"
        );

        $details = $event->getDetails();

        return $this->render('home/show.html.twig', [
            'event' => $event,
            'details' => $details,
        ]);
    }

    #[Route('/home/{id}/participation/create', name: 'app_home_participation_create')]
    public function create($id, EntityManagerInterface $entityManager, ParticipationRequestRepository $participationRequestRepository): Response
    {
        $user = $this->getUser();
        $musician = $user->getMusician();

        $event = $entityManager->getRepository(Event::class)->find($id);

        // Verificar si ya existe una solicitud de participación para este evento y músico
        $existingParticipation = $participationRequestRepository->findOneBy([
            'event' => $event,
            'musician' => $musician
        ]);

        if ($existingParticipation) {
            $this->addFlash(
                'danger',
                "Ya has enviado una solicitud de participación para este evento."
            );

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $participation = new ParticipationRequest();
        $participation->setEvent($event);
        $participation->setState('In process');
        $participation->setApplicationDate(new DateTime());
        $participation->setMusician($musician);

        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "S'ha enviat una sol.licitud per a participar en l'event."
        );

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

}
