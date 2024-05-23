<?php

namespace App\Controller;

use App\Entity\Details;
use App\Entity\Event;
use App\Form\EventBackType;
use App\Repository\DetailsRepository;
use App\Repository\EventRepository;
use App\Repository\ParticipationRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/event')]
class BackEventController extends AbstractController
{
    #[Route('/', name: 'app_back_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('back_event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventBackType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('back_event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventBackType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_event_delete', methods: ['POST'])]
    public function delete(EventRepository $eventRepository, Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->beginTransaction();

            try {
                foreach ($event->getParticipationRequests() as $participationRequest) {
                    $entityManager->remove($participationRequest);
                }

                foreach ($event->getDetails() as $detail) {
                    $entityManager->remove($detail);
                }

                $entityManager->remove($event);
                $entityManager->flush();

                $entityManager->commit();
            } catch (\Exception $e) {
                $entityManager->rollback();
                throw $e;
            }
        }

        return $this->redirectToRoute('app_back_event_index', [], Response::HTTP_SEE_OTHER);
    }

}