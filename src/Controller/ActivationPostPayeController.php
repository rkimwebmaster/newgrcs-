<?php

namespace App\Controller;

use App\Entity\ActivationPostPaye;
use App\Entity\ComptePetitClient;
use App\Form\ActivationPostPayeType;
use App\Repository\ActivationPostPayeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activation/post/paye')]
class ActivationPostPayeController extends AbstractController
{
    #[Route('/', name: 'app_activation_post_paye_index', methods: ['GET'])]
    public function index(ActivationPostPayeRepository $activationPostPayeRepository): Response
    {
        $objets=$activationPostPayeRepository->findAll();
        if(!$objets){
            $this->addFlash('info', 'Aucun élément dans le système.');
        }
        return $this->render('activation_post_paye/index.html.twig', [
            'activation_post_payes' => $objets,
        ]);
    }

    #[Route('/new/{id}', name: 'app_activation_post_paye_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComptePetitClient $comptePetitClient, ActivationPostPayeRepository $activationPostPayeRepository): Response
    {
        $activationPostPaye = new ActivationPostPaye($comptePetitClient);
        $form = $this->createForm(ActivationPostPayeType::class, $activationPostPaye);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activationPostPayeRepository->add($activationPostPaye, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_activation_post_paye_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activation_post_paye/new.html.twig', [
            'activation_post_paye' => $activationPostPaye,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activation_post_paye_show', methods: ['GET'])]
    public function show(ActivationPostPaye $activationPostPaye): Response
    {
        return $this->render('activation_post_paye/show.html.twig', [
            'activation_post_paye' => $activationPostPaye,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activation_post_paye_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActivationPostPaye $activationPostPaye, ActivationPostPayeRepository $activationPostPayeRepository): Response
    {
        $form = $this->createForm(ActivationPostPayeType::class, $activationPostPaye);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activationPostPayeRepository->add($activationPostPaye, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_activation_post_paye_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activation_post_paye/edit.html.twig', [
            'activation_post_paye' => $activationPostPaye,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/activationPostPayeCloture', name: 'app_activation_post_paye_cloture', methods: ['GET', 'POST'])]
    public function activationPostPayeCloture(Request $request, ActivationPostPaye $activationPostPaye, ActivationPostPayeRepository $activationPostPayeRepository): Response
    {

        $activationPostPaye->setIsCloture(true);
        $activationPostPayeRepository->add($activationPostPaye, true);
        $this->addFlash('success', 'Opération réussie.');

        return $this->redirectToRoute('app_activation_post_paye_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}', name: 'app_activation_post_paye_delete', methods: ['POST'])]
    public function delete(Request $request, ActivationPostPaye $activationPostPaye, ActivationPostPayeRepository $activationPostPayeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activationPostPaye->getId(), $request->request->get('_token'))) {
            $activationPostPayeRepository->remove($activationPostPaye, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_activation_post_paye_index', [], Response::HTTP_SEE_OTHER);
    }
}
