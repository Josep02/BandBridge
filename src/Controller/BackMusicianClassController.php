<?php

namespace App\Controller;

use App\Entity\MusicianClass;
use App\Form\MusicianClassType;
use App\Repository\MusicianClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/back/musician/class')]
class BackMusicianClassController extends AbstractController
{
    #[Route('/', name: 'app_back_musician_class_index', methods: ['GET'])]
    public function index(MusicianClassRepository $musicianClassRepository): Response
    {
        return $this->render('back_musician_class/index.html.twig', [
            'musician_classes' => $musicianClassRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_musician_class_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $musicianClass = new MusicianClass();
        $form = $this->createForm(MusicianClassType::class, $musicianClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($musicianClass);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_musician_class_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_musician_class/new.html.twig', [
            'musician_class' => $musicianClass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_musician_class_show', methods: ['GET'])]
    public function show(MusicianClass $musicianClass): Response
    {
        return $this->render('back_musician_class/show.html.twig', [
            'musician_class' => $musicianClass,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_musician_class_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MusicianClass $musicianClass, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MusicianClassType::class, $musicianClass);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_musician_class_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back_musician_class/edit.html.twig', [
            'musician_class' => $musicianClass,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_musician_class_delete', methods: ['POST'])]
    public function delete(Request $request, MusicianClass $musicianClass, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musicianClass->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($musicianClass);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_musician_class_index', [], Response::HTTP_SEE_OTHER);
    }
}