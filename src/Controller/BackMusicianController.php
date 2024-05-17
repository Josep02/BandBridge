<?php

namespace App\Controller;

use App\Entity\Musician;
use App\Form\BackMusicianType;
use App\Repository\MusicianClassRepository;
use App\Repository\MusicianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/musician')]
class BackMusicianController extends AbstractController
{
    #[Route('/', name: 'app_back_musician_index', methods: ['GET'])]
    public function index(MusicianRepository $musicianRepository): Response
    {
        return $this->render('back_musician/index.html.twig', [
            'musicians' => $musicianRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_musician_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $musician = new Musician();
        $form = $this->createForm(BackMusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($musician);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_musician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_musician/new.html.twig', [
            'musician' => $musician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_musician_show', methods: ['GET'])]
    public function show(Musician $musician): Response
    {
        return $this->render('back_musician/show.html.twig', [
            'musician' => $musician,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_musician_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musician $musician, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BackMusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_musician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_musician/edit.html.twig', [
            'musician' => $musician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_musician_delete', methods: ['POST'])]
    public function delete(Request $request, Musician $musician, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musician->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($musician);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_musician_index', [], Response::HTTP_SEE_OTHER);
    }
}
