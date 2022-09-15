<?php

namespace App\Controller;

use App\Entity\ApprovisionnementGRCS;
use App\Entity\CompteGRCS;
use App\Entity\GrandFournisseur;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ApprovisionnementGRCSType;
use App\Repository\ApprovisionnementGRCSRepository;
use App\Repository\CompteGRCSRepository;
use App\Repository\GRCSRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/approvisionnement/g/r/c/s')]
class ApprovisionnementGRCSController extends AbstractController
{
    #[Route('/', name: 'app_approvisionnement_g_r_c_s_index', methods: ['GET'])]
    public function index(ApprovisionnementGRCSRepository $approvisionnementGRCSRepository): Response
    {
        $objets=$approvisionnementGRCSRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucune donnée dans le système.');
        }

        if($this->isGranted('ROLE_ADMIN_GD_FSS')){
            $grandFournisseur= $this->getUser()->getGrandFournisseur();

            $check=$grandFournisseur->getCompteGRCS()->getGrandFournisseur()===$grandFournisseur;

            if($check){
                $compteGRCS=$grandFournisseur->getCompteGRCS()->getGrandFournisseur()===$grandFournisseur;
            }else{
                return 0;
            }
            // dd($compteGRCS);

            $appro= $approvisionnementGRCSRepository->findBy(['compteGRCS'=>$grandFournisseur],['id'=>"desc"]);
            return $this->render('approvisionnement_grcs/index.html.twig', [
                'approvisionnement_g_r_cs' => $appro,
            ]);
        }

        return $this->render('approvisionnement_grcs/index.html.twig', [
            'approvisionnement_g_r_cs' => $approvisionnementGRCSRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_approvisionnement_g_r_c_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GrandFournisseur $grandFournisseur, GRCSRepository $gRCSRepository, CompteGRCSRepository $compteGRCSRepository, ApprovisionnementGRCSRepository $approvisionnementGRCSRepository): Response
    {
        $compteGRCS=$compteGRCSRepository->findOneBy(['grandFournisseur'=>$grandFournisseur]);
        $approvisionnementGRC = new ApprovisionnementGRCS($compteGRCS);
        $form = $this->createForm(ApprovisionnementGRCSType::class, $approvisionnementGRC);
        $form->handleRequest($request);

        $user=$this->getUser();
        $approvisionnementGRC->setUtilisateur($user);

        if ($form->isSubmitted() && $form->isValid()) {
            //creation du message de notofication 
            $grcs=$compteGRCS->getGrcs();
            $message=new Message();
            $contenu="Bonjour, Nous tenons à vous informé ce jour que votre compte ".$compteGRCS." a été approvisionné par ". $grcs. ". Cordialement.";
            $message->setSujet('Compte approvionné')->setContenu($contenu);
            $grcs->addMessage($message);
            //fin 
            $approvisionnementGRCSRepository->add($approvisionnementGRC, true);
            $gRCSRepository->add($grcs,true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_compte_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
            // return $this->redirectToRoute('app_approvisionnement_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('approvisionnement_grcs/new.html.twig', [
            'approvisionnement_g_r_c' => $approvisionnementGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_approvisionnement_g_r_c_s_show', methods: ['GET'])]
    public function show(ApprovisionnementGRCS $approvisionnementGRC): Response
    {
        return $this->render('approvisionnement_grcs/show.html.twig', [
            'approvisionnement_g_r_c' => $approvisionnementGRC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_approvisionnement_g_r_c_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApprovisionnementGRCS $approvisionnementGRC, ApprovisionnementGRCSRepository $approvisionnementGRCSRepository): Response
    {
        $form = $this->createForm(ApprovisionnementGRCSType::class, $approvisionnementGRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $approvisionnementGRCSRepository->add($approvisionnementGRC, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_approvisionnement_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('approvisionnement_grcs/edit.html.twig', [
            'approvisionnement_g_r_c' => $approvisionnementGRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_approvisionnement_g_r_c_s_delete', methods: ['POST'])]
    public function delete(Request $request, ApprovisionnementGRCS $approvisionnementGRC, ApprovisionnementGRCSRepository $approvisionnementGRCSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$approvisionnementGRC->getId(), $request->request->get('_token'))) {
            $approvisionnementGRCSRepository->remove($approvisionnementGRC, true);
            $this->addFlash('success','Opération réussie.');

        }

        return $this->redirectToRoute('app_approvisionnement_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
