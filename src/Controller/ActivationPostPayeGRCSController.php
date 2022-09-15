<?php

namespace App\Controller;

use App\Entity\ActivationPostPayeGRCS;
use App\Entity\CompteGRCS;
use App\Entity\Message;
use App\Form\ActivationPostPayeGRCSType;
use App\Repository\ActivationPostPayeGRCSRepository;
use App\Repository\GRCSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activation/post/paye/g/r/c/s')]
class ActivationPostPayeGRCSController extends AbstractController
{
    #[Route('/', name: 'app_activation_post_paye_g_r_c_s_index', methods: ['GET'])]
    public function index(ActivationPostPayeGRCSRepository $activationPostPayeGRCSRepository): Response
    {
        return $this->render('activation_post_paye_grcs/index.html.twig', [
            'activation_post_paye_g_r_cs' => $activationPostPayeGRCSRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_activation_post_paye_g_r_c_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompteGRCS $compteGRCS, GRCSRepository $gRCSRepository, ActivationPostPayeGRCSRepository $activationPostPayeGRCSRepository): Response
    {
        //verifier qu'une autre activation non cloturé pour le meme compte existe dans la base de donnée 
        $check= $activationPostPayeGRCSRepository->findOneBy(['compteGRCS'=>$compteGRCS,'isCloture'=>false]);
        if($check){
            $this->addFlash('warning','Une autre activation pendante non cloturé existe : '. $compteGRCS);
            return $this->redirectToRoute('app_activation_post_paye_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }
        $activationPostPayeGRC = new ActivationPostPayeGRCS($compteGRCS);
        $form = $this->createForm(ActivationPostPayeGRCSType::class, $activationPostPayeGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $grcs=$compteGRCS->getGrcs();
            $message=new Message();
            $contenu="Bonjour, Nous tenons à vous informé ce jour que votre compte ".$compteGRCS." est passé en mode postpayé. Cordialement.";
            $message->setSujet('Passage en mode postpayé')->setContenu($contenu);
            $grcs->addMessage($message);
            $gRCSRepository->add($grcs,true);

            
            $activationPostPayeGRCSRepository->add($activationPostPayeGRC, true);

            $this->addFlash('success','Opération réussie');

            return $this->redirectToRoute('app_activation_post_paye_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activation_post_paye_grcs/new.html.twig', [
            'activation_post_paye_g_r_c' => $activationPostPayeGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activation_post_paye_g_r_c_s_show', methods: ['GET'])]
    public function show(ActivationPostPayeGRCS $activationPostPayeGRC): Response
    {
        return $this->render('activation_post_paye_grcs/show.html.twig', [
            'activation_post_paye_g_r_c' => $activationPostPayeGRC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activation_post_paye_g_r_c_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActivationPostPayeGRCS $activationPostPayeGRC, ActivationPostPayeGRCSRepository $activationPostPayeGRCSRepository): Response
    {
        $form = $this->createForm(ActivationPostPayeGRCSType::class, $activationPostPayeGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activationPostPayeGRCSRepository->add($activationPostPayeGRC, true);

            return $this->redirectToRoute('app_activation_post_paye_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activation_post_paye_grcs/edit.html.twig', [
            'activation_post_paye_g_r_c' => $activationPostPayeGRC,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/cloturer', name: 'app_activation_post_paye_g_r_c_s_cloturer', methods: ['GET', 'POST'])]
    public function cloturer(Request $request, ActivationPostPayeGRCS $activationPostPayeGRC, ActivationPostPayeGRCSRepository $activationPostPayeGRCSRepository): Response
    {
        $activationPostPayeGRC->setIsCloture(true);
        $activationPostPayeGRCSRepository->add($activationPostPayeGRC, true);
        $this->addFlash('success','Opération réussie. Vous avez cloturé '. $activationPostPayeGRC);

        return $this->redirectToRoute('app_activation_post_paye_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_activation_post_paye_g_r_c_s_delete', methods: ['POST'])]
    public function delete(Request $request, ActivationPostPayeGRCS $activationPostPayeGRC, ActivationPostPayeGRCSRepository $activationPostPayeGRCSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activationPostPayeGRC->getId(), $request->request->get('_token'))) {
            $activationPostPayeGRCSRepository->remove($activationPostPayeGRC, true);
        }

        return $this->redirectToRoute('app_activation_post_paye_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
