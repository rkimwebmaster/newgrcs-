<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN_GRCS')){
            return $this->redirectToRoute('app_message_grcs_index', [], Response::HTTP_SEE_OTHER);
        }elseif($this->isGranted('ROLE_ADMIN_GD_FSS')){
            return $this->redirectToRoute('app_message_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }elseif($this->isGranted('ROLE_ADMIN_PT_CL')){
            return $this->redirectToRoute('app_message_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash('info','Tous les messages des utilisateurs.');
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/indexSysteme', name: 'app_message_systeme_index', methods: ['GET'])]
    public function indexSysteme(MessageRepository $messageRepository): Response
    {
        $user=$this->getUser();
        // $structure=$user
        $messages=$messageRepository->findBy();
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    
    #[Route('/indexGRCS', name: 'app_message_grcs_index', methods: ['GET'])]
    public function indexGRCS(MessageRepository $messageRepository): Response
    {
        $grcs=$this->getUser()->getGRCS();
        $grcs->reinitialiserMessage();
        $messages=$messageRepository->findBy(['destinataireGRCS'=>$grcs],['createdAt'=>'DESC']);
        // 
        foreach($messages as $message){
            $message->setIsLu(true);
            $messageRepository->add($message, true);
        }

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    
    #[Route('/indexFSS', name: 'app_message_grand_fournisseur_index', methods: ['GET'])]
    public function indexFss(MessageRepository $messageRepository): Response
    {
        $grandFournisseur=$this->getUser()->getGrandFournisseur();
        $grandFournisseur->reinitialiserMessage();
        $messages=$messageRepository->findBy(['destinataireFournisseur'=>$grandFournisseur],['createdAt'=>'DESC']);
        foreach($messages as $message){
            $message->setIsLu(true);
            $messageRepository->add($message, true);
        }
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    
    #[Route('/indexCL', name: 'app_message_petit_client_index', methods: ['GET'])]
    public function indexCL(MessageRepository $messageRepository): Response
    {
       
        $petitClient=$this->getUser()->getPetitClient();
        $petitClient->reinitialiserMessage();
        $messages=$messageRepository->findBy(['destinataireClient'=>$petitClient],['createdAt'=>'DESC']);
        foreach($messages as $message){
            $message->setIsLu(true);
            $messageRepository->add($message, true);
        }
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/newSYS', name: 'app_message_newSYS', methods: ['GET', 'POST'])]
    public function newSYS(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/newGRCS', name: 'app_message_newGRCS', methods: ['GET', 'POST'])]
    public function newGRCS(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/newFSS', name: 'app_message_newFSS', methods: ['GET', 'POST'])]
    public function newFSS(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/newCL', name: 'app_message_newCL', methods: ['GET', 'POST'])]
    public function newCL(Request $request, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

   
    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $messageRepository->remove($message, true);
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
