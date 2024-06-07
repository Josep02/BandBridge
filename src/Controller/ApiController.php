<?php

namespace App\Controller;

use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/count', name: 'app_api_count')]
    public function index(InvitationRepository $invitationRepository): Response
    {
        $user = $this->getUser();

        $invitations = $invitationRepository->findOneBy(['state' => 'Pendiente', 'musician' => $user]);

        $totalInvitations = 0;

        if ($invitations) {
            $totalInvitations = count($invitations->getInvitations());
        }

        $response = $this->json(['count_number' => $totalInvitations]);

        return $response;
    }
}