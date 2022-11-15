<?php

namespace App\Controller;

use App\Entity\MessagePetitClient;
use App\Form\MessagePetitClientType;
use App\Repository\MessagePetitClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message/petit/client')]
class MessagePetitClientController extends AbstractController
{
    #[Route('/', name: 'app_message_petit_client_indexold', methods: ['GET'])]
    public function index(MessagePetitClientRepository $messagePetitClientRepository): Response
    {
        $objets=$messagePetitClientRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucune donnée dans le système.');
        }


        return $this->render('message_petit_client/index.html.twig', [
            'message_petit_clients' => $messagePetitClientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_message_petit_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessagePetitClientRepository $messagePetitClientRepository): Response
    {
        $messagePetitClient = new MessagePetitClient();
        $form = $this->createForm(MessagePetitClientType::class, $messagePetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messagePetitClientRepository->add($messagePetitClient, true);

            return $this->redirectToRoute('app_message_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_petit_client/new.html.twig', [
            'message_petit_client' => $messagePetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_petit_client_show', methods: ['GET'])]
    public function show(MessagePetitClient $messagePetitClient): Response
    {
        return $this->render('message_petit_client/show.html.twig', [
            'message_petit_client' => $messagePetitClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_petit_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MessagePetitClient $messagePetitClient, MessagePetitClientRepository $messagePetitClientRepository): Response
    {
        $form = $this->createForm(MessagePetitClientType::class, $messagePetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messagePetitClientRepository->add($messagePetitClient, true);

            return $this->redirectToRoute('app_message_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_petit_client/edit.html.twig', [
            'message_petit_client' => $messagePetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_petit_client_delete', methods: ['POST'])]
    public function delete(Request $request, MessagePetitClient $messagePetitClient, MessagePetitClientRepository $messagePetitClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messagePetitClient->getId(), $request->request->get('_token'))) {
            $messagePetitClientRepository->remove($messagePetitClient, true);
        }

        return $this->redirectToRoute('app_message_petit_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
