<?php

namespace App\Controller;

use App\Entity\MusicianClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Invitation;
use App\Entity\Musician;
use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/invitation')]
class InvitationController extends AbstractController
{
    #[Route('/new', name: 'app_invitation_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $musicianId = $request->request->get('musicianId');
        $organizationId = $request->request->get('organizationId');
        $message = $request->request->get('message');

        $defaultMessage = 'Te invitamos a formar parte de nuestra familia!';

        if (!$message) {
            $message = $defaultMessage;
        }

        $musician = $entityManager->getRepository(Musician::class)->find($musicianId);
        $organization = $entityManager->getRepository(Organization::class)->find($organizationId);

        if (!$musician || !$organization) {
            throw $this->createNotFoundException('No se encontró el músico o la organización');
        }

        $invitation = new Invitation();
        $invitation->setMusician($musician);
        $invitation->setMessage($message);
        $invitation->setOrganization($organization);
        $invitation->setState('Pendiente');

        $entityManager->persist($invitation);
        $entityManager->flush();

        return $this->redirectToRoute('app_organization_organizer_show', ['id' => $organizationId]);
    }

    #[Route('/{id}/accept', name: 'app_invitation_accept', methods: ['POST'])]
    public function accept(Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $invitation->setState('Aceptada');

        $musician = $invitation->getMusician();
        $organization = $invitation->getOrganization();

        $musicianClass = new MusicianClass();
        $musicianClass->setMusician($musician);
        $musicianClass->setRole('Musician');
        $musicianClass->setOrganization($organization);

        $entityManager->persist($musicianClass);
        $entityManager->flush();

        return $this->redirectToRoute('app_musician_show', ['id' => $invitation->getMusician()->getId()]);
    }

    #[Route('/{id}/reject', name: 'app_invitation_reject', methods: ['POST'])]
    public function reject(Invitation $invitation, EntityManagerInterface $entityManager): Response
    {
        $invitation->setState('Denegada');

        $entityManager->flush();

        return $this->redirectToRoute('app_musician_show', ['id' => $invitation->getMusician()->getId()]);
    }
}