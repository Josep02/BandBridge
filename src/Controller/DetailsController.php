<?php

namespace App\Controller;

use App\Entity\Details;
use App\Form\DetailsType;
use App\Repository\DetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/details')]
class DetailsController extends AbstractController
{
    #[Route('/', name: 'app_details_index', methods: ['GET'])]
    public function index(DetailsRepository $detailsRepository): Response
    {
        return $this->render('details/index.html.twig', [
            'details' => $detailsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_details_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $detail = new Details();
        $form = $this->createForm(DetailsType::class, $detail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($detail);
            $entityManager->flush();

            return $this->redirectToRoute('app_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details/new.html.twig', [
            'detail' => $detail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_show', methods: ['GET'])]
    public function show(Details $detail): Response
    {
        return $this->render('details/show.html.twig', [
            'detail' => $detail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_details_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Details $detail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DetailsType::class, $detail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('details/edit.html.twig', [
            'detail' => $detail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_details_delete', methods: ['POST'])]
    public function delete(Request $request, Details $detail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detail->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($detail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_details_index', [], Response::HTTP_SEE_OTHER);
    }
}
