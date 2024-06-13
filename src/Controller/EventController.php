<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Instrument;
use App\Entity\Organization;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicianRepository;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository,  MusicianRepository $musicianRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $musician = $musicianRepository->findOneBy(['login' => $user]);

        $instrument = $musician->getInstrument();

        $q = $request->query->get('q', '');

        if (empty($q)) {
            $query = $eventRepository->findForMe($instrument);
        } else {
            $query = $eventRepository->findByTextQuery($q);
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('event/index.html.twig', [
            'events' => $pagination,
            'pagination' => $pagination,
            'q' => $q
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Organization $organization, Request $request, EntityManagerInterface $entityManager, OrganizationRepository $organizationRepository, int $organizationId): Response
    {
        $organization = $organizationRepository->find($organizationId);

        $event = new Event();
        $event->setOrganization($organization);

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $organization = $event->getOrganization();

        $organizationId = $event->getOrganization()->getId();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organization_organizer_show', ['id' => $organizationId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'organization' => $organization,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $organization = $event->getOrganization()->getId();

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

        return $this->redirectToRoute('app_organization_organizer_show', ['id' => $organization], Response::HTTP_SEE_OTHER);
    }
}
