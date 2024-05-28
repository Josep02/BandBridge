<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\Musician;
use App\Form\MusicianType;
use App\Repository\MusicianClassRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipationRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/musician')]
class MusicianController extends AbstractController
{
    #[Route('/', name: 'app_musician_index', methods: ['GET'])]
    public function index(MusicianRepository $musicianRepository): Response
    {
        return $this->render('musician/index.html.twig', [
            'musicians' => $musicianRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_musician_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $musician = new Musician();
        $login = new Login();

        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();

            $login->setUsername($username);
            $hashedPassword = $passwordHasher->hashPassword($login, $password);
            $login->setPassword($hashedPassword);
            $login->setRole('ROLE_USER');

            $musician->setLogin($login);

            $entityManager->persist($login);
            $entityManager->persist($musician);
            $entityManager->flush();

            return $this->redirectToRoute('app_musician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('musician/new.html.twig', [
            'musician' => $musician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_musician_show', methods: ['GET'])]
    public function show(Musician $musician, MusicianClassRepository $musicianClassRepository, ParticipationRequestRepository $participationRequestRepository): Response
    {
        $organizer = !empty($musicianClassRepository->findBy(['musician' => $musician]));

        $events = $participationRequestRepository->findByState($musician, 'Accepted');

        return $this->render('musician/show.html.twig', [
            'musician' => $musician,
            'organizer' => $organizer,
            'events' => $events,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_musician_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musician $musician, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_musician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('musician/edit.html.twig', [
            'musician' => $musician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_musician_delete', methods: ['POST'])]
    public function delete(Request $request, Musician $musician, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musician->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($musician);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_musician_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/events', name: 'app_musician_events', methods: ['GET'])]
    public function events(Musician $musician, ParticipationRequestRepository $participationRequestRepository): Response
    {
        $participations = $participationRequestRepository->findByState($musician, 'In process');
        $refused = $participationRequestRepository->findByState($musician, 'Refused');
        $accepted = $participationRequestRepository->findByState($musician, 'Accepted');

        return $this->render('musician/events.html.twig', [
            'participations' => $participations,
            'refuseds' => $refused,
            'accepteds' => $accepted,
        ]);
    }

    #[Route('/{id}/organizations', name: 'app_musician_organizations', methods: ['GET'])]
    public function organizations(Musician $musician, MusicianClassRepository $musicianClassRepository): Response
    {
        $organizer = $musicianClassRepository->findBy(['musician' => $musician, 'role' => 'Organizer']);
        $musician = $musicianClassRepository->findBy(['musician' => $musician, 'role' => 'Musician']);

        return $this->render('musician/organizations.html.twig', [
            'organizer' => $organizer,
            'musician' => $musician,
        ]);
    }

}
