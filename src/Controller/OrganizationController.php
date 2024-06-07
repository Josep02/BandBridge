<?php
namespace App\Controller;

use App\Entity\Details;
use App\Entity\Event;
use App\Entity\MusicianClass;
use App\Entity\Organization;
use App\Entity\ParticipationRequest;
use App\Form\EventType;
use App\Form\OrganizationType;
use App\Repository\DetailsRepository;
use App\Repository\EventRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicianClassRepository;
use App\Repository\MusicianRepository;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationTypeRepository;
use App\Repository\ParticipationRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

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
    public function new(Request $request, Security $security, EntityManagerInterface $entityManager, OrganizationTypeRepository $organizationTypeRepository): Response
    {
        $user = $security->getUser();
        $musician = $user->getMusician();

        $types = $organizationTypeRepository->findAll();

        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();

            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $file->guessExtension();
                $fileName = $originalFileName . '.' . $fileExtension;
                $uploadDir = $this->getParameter('images');
                $filePath = $uploadDir . '/' . $fileName;

                $file->move($uploadDir, $fileName);

                $organization->setImage($fileName);
            } else {
                $organization->setImage('card.png');
            }

            $type = $request->request->get('type');
            $organizationType = $organizationTypeRepository->find($type);
            $organization->setOrganizationType($organizationType);

            $musicianClass = new MusicianClass();
            $musicianClass->setOrganization($organization);
            $musicianClass->setMusician($musician);
            $musicianClass->setRole('Organizer');

            $entityManager->persist($musicianClass);
            $entityManager->persist($organization);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Organizacion registrada correctamente."
            );

            return $this->redirectToRoute('app_musician_show', ['id' => $musician->getId()]);
        }

        return $this->render('organization/new.html.twig', [
            'organization' => $organization,
            'form' => $form,
            'types' => $types,
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
    public function organizer(Organization $organization, MusicianClassRepository $musicianClassRepository, EventRepository $eventRepository, MusicianRepository $musicianRepository): Response
    {
        $musicians = $musicianClassRepository->findBy(['organization' => $organization, 'role' => 'musician']);

        $events = $eventRepository->findBy(['organization' => $organization]);

        $allMusicians = $musicianRepository->findAll();

        return $this->render('organization/organizer_show.html.twig', [
            'organization' => $organization,
            'musicians' => $musicians,
            'events' => $events,
            'allMusicians' => $allMusicians,
        ]);
    }

    #[Route('/{organizationId}/new-event', name: 'app_organization_event_new', methods: ['GET', 'POST'])]
    public function newEvent($organizationId, Request $request, EntityManagerInterface $entityManager, OrganizationRepository $organizationRepository, InstrumentRepository $instrumentRepository): Response
    {
        $organization = $organizationRepository->find($organizationId);
        if (!$organization) {
            throw $this->createNotFoundException('Organization not found');
        }

        $instruments = $instrumentRepository->findAll();
        $numInstruments = count($instruments);

        $event = new Event();
        $event->setOrganization($organization);
        $event->setCreated(new \DateTime());

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);

            for ($i = 0; $i < $numInstruments; $i++) {
                $quantity = $request->request->get('quantity_' . $i);
                $minPayment = $request->request->get('min_payment_' . $i);
                $instrumentId = $request->request->get('instrument_' . $i);

                if ($quantity === null || $minPayment === null || $instrumentId === null) {
                    continue;
                }

                $details = new Details();
                $details->setEvent($event);
                $details->setQuantity((int)$quantity);
                $details->setMinPayment((float)$minPayment);

                $instrument = $instrumentRepository->find($instrumentId);
                if ($instrument) {
                    $details->setRequiredInstrument($instrument);
                }

                $entityManager->persist($details);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_organization_organizer_show', ['id' => $organizationId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'instruments' => $instruments,
            'organization' => $organization
        ]);
    }

    #[Route('/{organizationId}/organizer/event/{eventId}', name: 'app_organization_event_show', methods: ['GET'])]
    public function showEvent(
        $organizationId,
        $eventId,
        ParticipationRequestRepository $participationRequestRepository,
        InstrumentRepository $instrumentRepository,
        OrganizationRepository $organizationRepository,
        EventRepository $eventRepository,
        DetailsRepository $detailsRepository
    ): Response {
        $organization = $organizationRepository->find($organizationId);

        if (!$organization) {
            throw $this->createNotFoundException('Organization not found');
        }

        $event = $eventRepository->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $instruments = $instrumentRepository->findAll();
        $details = $detailsRepository->findBy(['Event' => $event]);
        $participations = $participationRequestRepository->findBy(['event' => $event, 'state' => 'In process']);

        $participationsAccepted = $participationRequestRepository->findBy(['event' => $event, 'state' => 'Accepted']);

        return $this->render('event/organizer_event.html.twig', [
            'organization' => $organization,
            'event' => $event,
            'details' => $details,
            'instruments' => $instruments,
            'participations' => $participations,
            'participationsAccepted' => $participationsAccepted,
        ]);
    }



    #[Route('/{organizationId}/organizer/event/{eventId}/new-detail', name: 'app_organization_event_detail_new', methods: ['POST'])]
    public function newDetail($organizationId, $eventId, Request $request, EntityManagerInterface $entityManager, InstrumentRepository $instrumentRepository, OrganizationRepository $organizationRepository, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($eventId);
        $organization = $organizationRepository->find($organizationId);

        if (!$event || !$organization) {
            throw $this->createNotFoundException('Event or Organization not found');
        }

        $numInstruments = $request->request->get('num_instruments');

        $numInstruments = intval($numInstruments);

        for ($i = 0; $i < $numInstruments; $i++) {
            $quantity = $request->request->get('quantity_' . $i);
            $minPayment = $request->request->get('min_payment_' . $i);
            $instrumentId = $request->request->get('instrument_' . $i);

            if ($quantity === null || $minPayment === null || $instrumentId === null) {
                continue;
            }

            $details = new Details();
            $details->setEvent($event);
            $details->setQuantity((int)$quantity);
            $details->setMinPayment((float)$minPayment);

            $instrument = $instrumentRepository->find($instrumentId);
            if ($instrument) {
                $details->setRequiredInstrument($instrument);
            }

            $entityManager->persist($details);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_organization_event_show', ['organizationId' => $organizationId, 'eventId' => $eventId], Response::HTTP_SEE_OTHER);
    }
}