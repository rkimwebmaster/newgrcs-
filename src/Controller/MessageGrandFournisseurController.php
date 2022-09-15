<?php

namespace App\Controller;

use App\Entity\MessageGrandFournisseur;
use App\Form\MessageGrandFournisseurType;
use App\Repository\MessageGrandFournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message/grand/fournisseur')]
class MessageGrandFournisseurController extends AbstractController
{
    #[Route('/', name: 'app_message_grand_fournisseur_index', methods: ['GET'])]
    public function index(MessageGrandFournisseurRepository $messageGrandFournisseurRepository): Response
    {

        if($this->isGranted('ROLE_ADMIN_GD_FSS')){
            $grandFournisseur=$this->getUser()->getGrandFournisseur();
            $objets=$messageGrandFournisseurRepository->findAll();
            if(!$objets){
                $this->addFlash('info','Aucun message dans le système.');
            }
            return $this->render('message_grand_fournisseur/index.html.twig', [
                'message_grand_fournisseurs' => $messageGrandFournisseurRepository->findAll(),
            ]);
        }

        $objets=$messageGrandFournisseurRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucun message dans le système.');
        }

        return $this->render('message_grand_fournisseur/index.html.twig', [
            'message_grand_fournisseurs' => $messageGrandFournisseurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_message_grand_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessageGrandFournisseurRepository $messageGrandFournisseurRepository): Response
    {
        $messageGrandFournisseur = new MessageGrandFournisseur();
        $form = $this->createForm(MessageGrandFournisseurType::class, $messageGrandFournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageGrandFournisseurRepository->add($messageGrandFournisseur, true);

            return $this->redirectToRoute('app_message_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_grand_fournisseur/new.html.twig', [
            'message_grand_fournisseur' => $messageGrandFournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_grand_fournisseur_show', methods: ['GET'])]
    public function show(MessageGrandFournisseur $messageGrandFournisseur): Response
    {
        return $this->render('message_grand_fournisseur/show.html.twig', [
            'message_grand_fournisseur' => $messageGrandFournisseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_grand_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MessageGrandFournisseur $messageGrandFournisseur, MessageGrandFournisseurRepository $messageGrandFournisseurRepository): Response
    {
        $form = $this->createForm(MessageGrandFournisseurType::class, $messageGrandFournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageGrandFournisseurRepository->add($messageGrandFournisseur, true);

            return $this->redirectToRoute('app_message_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_grand_fournisseur/edit.html.twig', [
            'message_grand_fournisseur' => $messageGrandFournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_grand_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, MessageGrandFournisseur $messageGrandFournisseur, MessageGrandFournisseurRepository $messageGrandFournisseurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messageGrandFournisseur->getId(), $request->request->get('_token'))) {
            $messageGrandFournisseurRepository->remove($messageGrandFournisseur, true);
        }

        return $this->redirectToRoute('app_message_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
