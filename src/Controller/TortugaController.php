<?php

namespace App\Controller;

use App\Entity\Tortuga;
use App\Form\TortugaType;
use App\Repository\TortugaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tortuga')]
class TortugaController extends AbstractController
{
    #[Route('/', name: 'app_tortuga_index', methods: ['GET'])]
    public function index(TortugaRepository $tortugaRepository): Response
    {
        return $this->render('tortuga/index.html.twig', [
            'tortugas' => $tortugaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tortuga_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tortuga = new Tortuga();
        $form = $this->createForm(TortugaType::class, $tortuga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tortuga);
            $entityManager->flush();

            return $this->redirectToRoute('app_tortuga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tortuga/new.html.twig', [
            'tortuga' => $tortuga,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tortuga_show', methods: ['GET'])]
    public function show(Tortuga $tortuga): Response
    {
        return $this->render('tortuga/show.html.twig', [
            'tortuga' => $tortuga,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tortuga_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tortuga $tortuga, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TortugaType::class, $tortuga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tortuga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tortuga/edit.html.twig', [
            'tortuga' => $tortuga,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tortuga_delete', methods: ['POST'])]
    public function delete(Request $request, Tortuga $tortuga, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tortuga->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($tortuga);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tortuga_index', [], Response::HTTP_SEE_OTHER);
    }
}
