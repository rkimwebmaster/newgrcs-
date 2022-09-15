<?php

namespace App\Controller;

use App\Entity\PetitClient;
use App\Entity\User;
use App\Entity\GRCS;
use App\Form\PetitClientParticulierType;
use App\Form\PetitClientType;
use App\Repository\CompteGRCSRepository;
use App\Repository\GRCSRepository;
use App\Repository\PetitClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/petitclient')]
class PetitClientController extends AbstractController
{
    #[Route('/', name: 'app_petit_client_index', methods: ['GET'])]
    public function index(PetitClientRepository $petitClientRepository, CompteGRCSRepository $compteGRCSRepository): Response
    {
        $objets=$petitClientRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucun enregistrement dans le système.');
        }
        $comptesGRCS=$compteGRCSRepository->findAll();
        return $this->render('petit_client/index.html.twig', [
            'petit_clients' => $objets ,
            'comptes_grcs' => $comptesGRCS ,
        ]);
    }

    #[Route('/new', name: 'app_petit_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, PetitClientRepository $petitClientRepository, GRCSRepository $gRCSRepository): Response
    {
        $grcs=$gRCSRepository->findOneBy([]);
        if(!$grcs){
            $this->addFlash('info','Aucun grcs dans le système. Créez-en un au préalable.');
            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);  
        }
        $petitClient = new PetitClient();
        $form = $this->createForm(PetitClientType::class, $petitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$this->registerPtCl($petitClient, $userPasswordHasher, $entityManager);

            $petitClientRepository->add($petitClient, true);
            $this->addFlash('success','Opération réussie. Vous avez créez un client entreprise.');

            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('petit_client/new.html.twig', [
            'petit_client' => $petitClient,
            'form' => $form,
        ]);
    }

    #[Route('/newParticulier', name: 'app_petit_client_particulier_new', methods: ['GET', 'POST'])]
    public function newParticulier(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, PetitClientRepository $petitClientRepository, GRCSRepository $gRCSRepository): Response
    {
        $grcs=$gRCSRepository->findOneBy([]);
        if(!$grcs){
            $this->addFlash('info','Aucun grcs dans le système. Créez-en un au préalable.');
            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);  
        }
        $petitClient = new PetitClient();
        $petitClient->isIsParticulier(true);
        $form = $this->createForm(PetitClientParticulierType::class, $petitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$this->registerPtCl($petitClient, $userPasswordHasher, $entityManager);

            $petitClientRepository->add($petitClient, true);
            $this->addFlash('success','Opération réussie. Vous avez créez un client particulier.');

            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('petit_client/new.html.twig', [
            'petit_client' => $petitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_petit_client_show', methods: ['GET'])]
    public function show(PetitClient $petitClient): Response
    {
        return $this->render('petit_client/show.html.twig', [
            'petit_client' => $petitClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_petit_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PetitClient $petitClient, PetitClientRepository $petitClientRepository): Response
    {
        $form = $this->createForm(PetitClientType::class, $petitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $petitClientRepository->add($petitClient, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('petit_client/edit.html.twig', [
            'petit_client' => $petitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_petit_client_delete', methods: ['POST'])]
    public function delete(Request $request, PetitClient $petitClient, PetitClientRepository $petitClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$petitClient->getId(), $request->request->get('_token'))) {
            $petitClientRepository->remove($petitClient, true);
            $this->addFlash('success','Opération réussie.');

        }

        return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
    }

    
    private function registerPtCl(PetitClient $petitClient, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): User
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN_PT_CL']);
        $uniqueGRCS = $entityManager->getRepository(GRCS::class)->findOneBy([]);
        if (!$uniqueGRCS) {
            $this->addFlash('danger', 'Aucun GRCS configuré dans le système.');
            return $this->redirectToRoute('app_accueil');
        }
        $email = $petitClient->getAdresse()->getEmail();
        $user->setEmail($email);
        $user->setPetitClient($petitClient);
        // dd('teston');
        $password=uniqid('PTCL-');
        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        // dd('test');
        $entityManager->persist($user);
        // $entityManager->flush();
        $this->addFlash('success', 'Vous avez créez un utilisateur admin. petit client. Login: '.$email.' Mot de passe : '.$password);
        return $user;
    }

}
