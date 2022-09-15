<?php

namespace App\Controller;

use App\Entity\CompteGRCS;
use App\Entity\ComptePetitClient;
use App\Entity\PetitClient;
use App\Form\ComptePetitClientType;
use App\Repository\CompteGRCSRepository;
use App\Repository\ComptePetitClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comptepetitclient')]
class ComptePetitClientController extends AbstractController
{
    #[Route('/', name: 'app_compte_petit_client_index', methods: ['GET'])]
    public function index(ComptePetitClientRepository $comptePetitClientRepository, CompteGRCSRepository $compteGRCSRepository): Response
    {

        if($this->isGranted('ROLE_ADMIN_PT_CL')){
            
            $objets=$comptePetitClientRepository->findBy(['petitClient'=>$this->getUser()->getPetitClient()]);
            if(!$objets){
                $this->addFlash('info','Aucun compte client dans le système. Contactez l\'admin pour en créez un au préalable. ');

            }
            return $this->redirectToRoute('app_compte_petit_client_indexindexPetitClient', ['id'=>$this->getUser()->getPetitClient()->getId()], Response::HTTP_SEE_OTHER);

        }
        $objets=$comptePetitClientRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucun compte client dans le système. Créez-en un au préalable. ');
            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);

        }
        $comptes=$compteGRCSRepository->findAll();
        return $this->render('compte_petit_client/index.html.twig', [
            'compte_petit_clients' => $comptePetitClientRepository->findAll(),
            'comptes' => $comptes ,
        ]);
    }

    #[Route('/indexPetitClient/{id}', name: 'app_compte_petit_client_indexindexPetitClient', methods: ['GET'])]
    public function indexPetitClient(ComptePetitClientRepository $comptePetitClientRepository, PetitClient $petitClient): Response
    {

        // dd($petitClient);
        $comptePetitClients=$comptePetitClientRepository->findByPetitClient($petitClient);
        return $this->render('compte_petit_client/index.html.twig', [
            'compte_petit_clients' => $comptePetitClients,
            'petit_client' => $petitClient,
        ]);
    }

    #[Route('/new/{id}/{idCompte}/', name: 'app_compte_petit_client_new', methods: ['GET', 'POST'])]
    public function new(PetitClient $petitClient, CompteGRCS $compteGRCS, Request $request,CompteGRCSRepository $compteGRCSRepository, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        // dd($petitClient);
        $checkCompteGRCS=$compteGRCSRepository->findOneBy([]);
        if(!$checkCompteGRCS){
            $this->addFlash('danger','Aucun compte GCRS dans le systeme.');
            return $this->redirectToRoute('app_compte_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);

        }else{
            $check=$comptePetitClientRepository->findBy(['petitClient'=>$petitClient,'compteGRCS'=>$checkCompteGRCS]);
            if($check){
                $this->addFlash('danger','Impossible de créer 2 comptes petits clients pour le même compte GRCS.');  
                return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);

            }
        }
        $comptePetitClient = new ComptePetitClient($petitClient, $compteGRCS);

        ///////persitement 
        $comptePetitClientRepository->add($comptePetitClient, true);
        $this->addFlash('success','Opération réussie. Vous avez créez un compte de '.$petitClient.' à partir de '.$compteGRCS);
        return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_compte_petit_client_index', [], Response::HTTP_SEE_OTHER);

        ///fin 


        $form = $this->createForm(ComptePetitClientType::class, $comptePetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptePetitClientRepository->add($comptePetitClient, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_compte_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash('info','choisir le compte GRCS où affecté le compte client.');
        return $this->renderForm('compte_petit_client/new.html.twig', [
            'compte_petit_client' => $comptePetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_petit_client_show', methods: ['GET'])]
    public function show(ComptePetitClient $comptePetitClient): Response
    {
        return $this->render('compte_petit_client/show.html.twig', [
            'compte_petit_client' => $comptePetitClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compte_petit_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ComptePetitClient $comptePetitClient, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        $form = $this->createForm(ComptePetitClientType::class, $comptePetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comptePetitClientRepository->add($comptePetitClient, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_compte_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_petit_client/edit.html.twig', [
            'compte_petit_client' => $comptePetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_petit_client_delete', methods: ['POST'])]
    public function delete(Request $request, ComptePetitClient $comptePetitClient, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comptePetitClient->getId(), $request->request->get('_token'))) {
            $comptePetitClientRepository->remove($comptePetitClient, true);
            $this->addFlash('success','Opération réussie.');

        }

        return $this->redirectToRoute('app_compte_petit_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
