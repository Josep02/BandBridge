<?php

namespace App\Controller;

use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/count', name: 'app_api_count')]
    public function index(InvitationRepository $invitationRepository): Response
    {
        $user = $this->getUser();
        $totalInvitations = 0;

        if ($user instanceof UserInterface) {
            $invitations = $invitationRepository->findBy(['state' => 'Pendiente', 'musician' => $user]);
            $totalInvitations = count($invitations);
        }

        return $this->json(['count_number' => $totalInvitations]);
    }
}