<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Form\BackOrganizationType;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/organization')]
class BackOrganizationController extends AbstractController
{
    #[Route('/', name: 'app_back_organization_index', methods: ['GET'])]
    public function index(OrganizationRepository $organizationRepository): Response
    {
        return $this->render('back_organization/index.html.twig', [
            'organizations' => $organizationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_organization_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organization = new Organization();
        $form = $this->createForm(BackOrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_organization/new.html.twig', [
            'organization' => $organization,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_organization_show', methods: ['GET'])]
    public function show(Organization $organization): Response
    {
        return $this->render('back_organization/show.html.twig', [
            'organization' => $organization,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BackOrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_organization/edit.html.twig', [
            'organization' => $organization,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_organization_delete', methods: ['POST'])]
    public function delete(Request $request, Organization $organization, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organization->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($organization);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_organization_index', [], Response::HTTP_SEE_OTHER);
    }
}
