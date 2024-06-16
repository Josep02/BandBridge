<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Musician;
use App\Entity\ParticipationRequest;
use App\Repository\DetailsRepository;
use App\Repository\EventRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipationRequestRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(
    new Expression(
        'is_granted("ROLE_USER", subject)'
    ))]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_back');
        }

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
        $details = $event->getDetails();

        return $this->render('home/show.html.twig', [
            'event' => $event,
            'details' => $details,
        ]);
    }

    #[Route('/home/{id}/participation/create', name: 'app_home_participation_create')]
    public function create($id, EntityManagerInterface $entityManager, DetailsRepository $detailsRepository, ParticipationRequestRepository $participationRequestRepository): Response
    {
        $user = $this->getUser();
        $musician = $user->getMusician();

        $instrument = $musician->getInstrument();

        $event = $entityManager->getRepository(Event::class)->find($id);

        $existingParticipation = $participationRequestRepository->findOneBy([
            'event' => $event,
            'musician' => $musician
        ]);

        $details = $detailsRepository->findOneBy(['Event' => $event, 'requiredInstrument' => $instrument]);

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
        $participation->setDetail($details);

        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "Solicitud enviada correctamente"
        );

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

}
