<?php

namespace App\Controller;

use App\Entity\MessageGRCS;
use App\Form\MessageGRCSType;
use App\Repository\GRCSRepository;
use App\Repository\MessageGRCSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message/g/r/c/s')]
class MessageGRCSController extends AbstractController
{
    #[Route('/', name: 'app_message_g_r_c_s_index', methods: ['GET'])]
    public function index(MessageGRCSRepository $messageGRCSRepository): Response
    {
        $objets = $messageGRCSRepository->findBy([], ['date' => 'desc']);
        if (!$objets) {
            $this->addFlash('info', 'Aucun message dans le système.');
        }
        if ($this->isGranted('ROLE_ADMIN_PT_CL')) {

            ///ici on doit créer une methode dans le repository qui recupère les 
            // messages des users de ce meme client 
            return $this->render('message_grcs/index.html.twig', [
                'message_g_r_cs' => $messageGRCSRepository->findAll(),
            ]);
        }


        return $this->render('message_grcs/index.html.twig', [
            'message_g_r_cs' => $messageGRCSRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_message_g_r_c_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GRCSRepository $gRCSRepository, MessageGRCSRepository $messageGRCSRepository): Response
    {
        $grcs = $gRCSRepository->findOneBy([]);
        if (!$grcs) {
            $this->addFlash('warning', 'GRCS doit être configuré au prélable.');
            return $this->redirectToRoute('app_message_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }
        $messageGRC = new MessageGRCS($grcs, $this->getUser());
        $form = $this->createForm(MessageGRCSType::class, $messageGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $grcs->incrementerMessage();

            ///mettre à jour GRCS
            $gRCSRepository->add($grcs, true);

            $messageGRCSRepository->add($messageGRC, true);
            $this->addFlash('success', 'Opération réussie. Message envoyé à ' . $grcs . '.');
            return $this->redirectToRoute('app_message_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_grcs/new.html.twig', [
            'message_g_r_c' => $messageGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_g_r_c_s_show', methods: ['GET'])]
    public function show(MessageGRCS $messageGRC): Response
    {
        return $this->render('message_grcs/show.html.twig', [
            'message_g_r_c' => $messageGRC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_g_r_c_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MessageGRCS $messageGRC, MessageGRCSRepository $messageGRCSRepository): Response
    {
        $form = $this->createForm(MessageGRCSType::class, $messageGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageGRCSRepository->add($messageGRC, true);

            return $this->redirectToRoute('app_message_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message_grcs/edit.html.twig', [
            'message_g_r_c' => $messageGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_g_r_c_s_delete', methods: ['POST'])]
    public function delete(Request $request, MessageGRCS $messageGRC, MessageGRCSRepository $messageGRCSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $messageGRC->getId(), $request->request->get('_token'))) {
            $messageGRCSRepository->remove($messageGRC, true);
        }

        return $this->redirectToRoute('app_message_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
