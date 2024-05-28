<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Organization;
use App\Form\EventType;
use App\Form\OrganizationType;
use App\Repository\EventRepository;
use App\Repository\MusicianClassRepository;
use App\Repository\OrganizationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organization')]
class OrganizationController extends AbstractController
{
    #[Route('/', name: 'app_organization_index', methods: ['GET'])]
    public function index(OrganizationRepository $organizationRepository): Response
    {
        return $this->render('organization/index.html.twig', [
            'organizations' => $organizationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_organization_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organization/new.html.twig', [
            'organization' => $organization,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organization_show', methods: ['GET'])]
    public function show(Organization $organization, MusicianClassRepository $musicianClassRepository): Response
    {
        $musicians = $musicianClassRepository->findBy([
            'organization' => $organization,
            'role' => 'musician',
        ]);

        return $this->render('organization/show.html.twig', [
            'organization' => $organization,
            'musicians' => $musicians,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('organization/edit.html.twig', [
            'organization' => $organization,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organization_delete', methods: ['POST'])]
    public function delete(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organization->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($organization);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_organization_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/organizer', name: 'app_organization_organizer_show', methods: ['GET'])]
    public function organizer(Organization $organization, MusicianClassRepository $musicianClassRepository, EventRepository $eventRepository): Response
    {
        $musicians = $musicianClassRepository->findBy(['organization' => $organization, 'role' => 'musician']);

        $events = $eventRepository->findBy(['organization' => $organization]);

        return $this->render('organization/organizer_show.html.twig', [
            'organization' => $organization,
            'musicians' => $musicians,
            'events' => $events,
        ]);
    }

    #[Route('/{organizationId}/new-event', name: 'app_organization_event_new', methods: ['GET', 'POST'])]
    public function newEvent($organizationId, Request $request, EntityManagerInterface $entityManager, OrganizationRepository $organizationRepository): Response
    {
        $organization = $organizationRepository->find($organizationId);
        if (!$organization) {
            throw $this->createNotFoundException('Organization not found');
        }

        $event = new Event();
        $event->setOrganization($organization);
        $event->setCreated(new DateTime());

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_organization_organizer_show', ['id' => $organizationId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
}
