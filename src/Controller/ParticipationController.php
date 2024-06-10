<?php

namespace App\Controller;

use App\Entity\ParticipationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    #[Route('/{id}/accept', name: 'app_participation_accept', methods: ['POST'])]
    public function accept(ParticipationRequest $participationRequest, EntityManagerInterface $entityManager): Response
    {
        $participationRequest->setState('Accepted');

        $quantity = $participationRequest->getDetail()->getQuantity();

        if ($quantity > 0) {
            $quantity--;
            $participationRequest->getDetail()->setQuantity($quantity);
        }

        $entityManager->persist($participationRequest);
        $entityManager->flush();

        $organizationId = $participationRequest->getEvent()->getOrganization()->getId();
        $eventId = $participationRequest->getEvent()->getId();

        return $this->redirectToRoute('app_organization_event_show', ['organizationId' => $organizationId, 'eventId' => $eventId], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/reject', name: 'app_participation_reject', methods: ['POST'])]
    public function reject(ParticipationRequest $participationRequest, EntityManagerInterface $entityManager): Response
    {
        $participationRequest->setState('Refused');

        $entityManager->flush();

        $organizationId = $participationRequest->getEvent()->getOrganization()->getId();
        $eventId = $participationRequest->getEvent()->getId();

        return $this->redirectToRoute('app_organization_event_show', [
            'organizationId' => $organizationId,
            'eventId' => $eventId
        ], Response::HTTP_SEE_OTHER);
    }
}
