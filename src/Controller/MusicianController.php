<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Entity\Login;
use App\Entity\Musician;
use App\Form\Musician1Type;
use App\Form\MusicianType;
use App\Repository\InstrumentRepository;
use App\Repository\InvitationRepository;
use App\Repository\MusicianClassRepository;
use App\Repository\MusicianRepository;
use App\Repository\ParticipationRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

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
    public function new(Request $request, EntityManagerInterface $entityManager, InstrumentRepository $instrumentRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $musician = new Musician();
        $login = new Login();

        $instruments = $entityManager->getRepository(Instrument::class)->findAll();

        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['avatar']->getData();

            if ($file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExtension = $file->guessExtension();
                $fileName = $originalFileName . '.' . $fileExtension;
                $uploadDir = $this->getParameter('images');
                $filePath = $uploadDir . '/' . $fileName;

                $file->move($uploadDir, $fileName);

                $musician->setImage($fileName);
            } else {
                $musician->setImage('example.png');
            }

            $instrumentoSeleccionado = $request->request->get('instrumento');
            $instrument = $instrumentRepository->find($instrumentoSeleccionado);

            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();

            $login->setUsername($username);
            $hashedPassword = $passwordHasher->hashPassword($login, $password);
            $login->setPassword($hashedPassword);
            $login->setRole('ROLE_USER');

            $musician->setLogin($login);
            $musician->setInstrument($instrument);

            $entityManager->persist($login);
            $entityManager->persist($musician);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('musician/new.html.twig', [
            'musician' => $musician,
            'form' => $form,
            'instruments' => $instruments,
        ]);
    }



    #[Route('/{id}/show', name: 'app_musician_show', methods: ['GET'])]
    public function show($id, MusicianRepository $musicianRepository, MusicianClassRepository $musicianClassRepository, ParticipationRequestRepository $participationRequestRepository): Response
    {
        $user = $this->getUser();

        $musician = $musicianRepository->findOneBy(['login' => $user]);

        if (!$musician) {
            throw $this->createNotFoundException('No se encontró el músico asociado con este usuario.');
        }

        $organizer = !empty($musicianClassRepository->findBy(['musician' => $musician]));

        $events = $participationRequestRepository->findByState($musician, 'Accepted');

        return $this->render('musician/show.html.twig', [
            'musician' => $musician,
            'organizer' => $organizer,
            'events' => $events,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_musician_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musician $musician, EntityManagerInterface $entityManager, Security $security, InstrumentRepository $instrumentRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $instruments = $entityManager->getRepository(Instrument::class)->findAll();

        $user = $security->getUser();
        $username = $user->getUsername();

        $form = $this->createForm(Musician1Type::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['avatar']->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('images'), $fileName);

                $musician->setImage($fileName);
            }

            $instrumentoSeleccionado = $request->request->get('instrumento');

            $instrument = $instrumentRepository->find($instrumentoSeleccionado);
            $musician->setInstrument($instrument);

            $entityManager->persist($musician);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Informacion actualizada correctamente"
            );

            return $this->redirectToRoute('app_musician_show', ['id' => $musician->getId()]);
        }

        return $this->render('musician/edit.html.twig', [
            'musician' => $musician,
            'form' => $form->createView(),
            'instruments' => $instruments,
            'username' => $username,
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

    #[Route('/{id}/invitations', name: 'app_musician_invitations', methods: ['GET'])]
    public function invitations(Musician $musician, InvitationRepository $invitationRepository): Response
    {
        $invitations = $invitationRepository->findBy([
            'musician' => $musician,
            'state' => 'Pendiente'
        ]);

        return $this->render('musician/invitations.html.twig', [
            'invitations' => $invitations,
        ]);
    }

}
