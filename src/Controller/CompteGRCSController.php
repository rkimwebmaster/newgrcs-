<?php

namespace App\Controller;

use App\Entity\CompteGRCS;
use App\Entity\GrandFournisseur;
use App\Entity\Message;
use App\Form\CompteGRCSType;
use App\Repository\CompteGRCSRepository;
use App\Repository\GRCSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte/g/r/c/s')]
class CompteGRCSController extends AbstractController
{
    #[Route('/', name: 'app_compte_g_r_c_s_index', methods: ['GET'])]
    public function index(CompteGRCSRepository $compteGRCSRepository): Response
    {
        $objets = $compteGRCSRepository->findAll();
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }
        return $this->render('compte_grcs/index.html.twig', [
            'compte_g_r_cs' => $compteGRCSRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_compte_g_r_c_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GrandFournisseur $grandFournisseur, GRCSRepository $gRCSRepository, CompteGRCSRepository $compteGRCSRepository): Response
    {
        $compteGRC = new CompteGRCS($grandFournisseur);

        $uniqueGRCS = $gRCSRepository->findOneBy([]);
        if (!$uniqueGRCS) {
            $this->addFlash('danger', 'Pas de GRCS dans le système. Créez-en un au prélable.');
            return $this->redirectToRoute('app_g_r_c_s_new', [], Response::HTTP_SEE_OTHER);
        } else {
            ///verifier si un compte avec le meme grand fournisseur existe 
            $check = $compteGRCSRepository->findOneBy(['grandFournisseur' => $grandFournisseur]);
            if ($check) {
                $this->addFlash('danger', 'Un compte avec le même grand fournisseur existe. ');
                return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
            }
            $compteGRC->setGrcs($uniqueGRCS);
            $compteGRC->setGrandFournisseur($grandFournisseur);
            $compteGRCSRepository->add($compteGRC, true);
            $this->addFlash('success', 'Opération réussie. Vous avez créer un compte GRCS. ');

            return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(CompteGRCSType::class, $compteGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteGRCSRepository->add($compteGRC, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_grcs/new.html.twig', [
            'compte_g_r_c' => $compteGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_g_r_c_s_show', methods: ['GET'])]
    public function show(CompteGRCS $compteGRC): Response
    {
        return $this->render('compte_grcs/show.html.twig', [
            'compte_g_r_c' => $compteGRC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compte_g_r_c_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteGRCS $compteGRC, CompteGRCSRepository $compteGRCSRepository): Response
    {
        $form = $this->createForm(CompteGRCSType::class, $compteGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteGRCSRepository->add($compteGRC, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_grcs/edit.html.twig', [
            'compte_g_r_c' => $compteGRC,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/desactiverPp', name: 'app_compte_g_r_c_s_desactiver_pp', methods: ['GET', 'POST'])]
    public function desactiverPp(Request $request, GRCSRepository $gRCSRepository, CompteGRCS $compteGRC, CompteGRCSRepository $compteGRCSRepository): Response
    {
        $compteGRC->setIsPostPaye(false);
        $compteGRCSRepository->add($compteGRC, true);

        $grcs = $compteGRC->getGrcs();
        $message = new Message();
        $contenu = "Bonjour, Nous tenons à vous informé ce jour que votre compte " . $compteGRC . " est desactivé du mode postpayé. Cordialement.";
        $message->setSujet('Desactivation du mode postpayé')->setContenu($contenu);
        $grcs->addMessage($message);
        $gRCSRepository->add($grcs, true);

        $this->addFlash('success', 'Opération réussie. Vous avez descativé le mode postpayé. ');

        return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_compte_g_r_c_s_delete', methods: ['POST'])]
    public function delete(Request $request, CompteGRCS $compteGRC, CompteGRCSRepository $compteGRCSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $compteGRC->getId(), $request->request->get('_token'))) {
            $compteGRCSRepository->remove($compteGRC, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
