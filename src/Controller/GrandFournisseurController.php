<?php

namespace App\Controller;

use App\Entity\CompteGRCS;
use App\Entity\GrandFournisseur;
use App\Entity\GRCS;
use App\Entity\User;
use App\Form\GrandFournisseurType;
use App\Repository\CompteGRCSRepository;
use App\Repository\GrandFournisseurRepository;
use App\Repository\GRCSRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grand/fournisseur')]
class GrandFournisseurController extends AbstractController
{
    #[Route('/', name: 'app_grand_fournisseur_index', methods: ['GET'])]
    public function index(GrandFournisseurRepository $grandFournisseurRepository): Response
    {
        $objets=$grandFournisseurRepository->findAll();
        if(!$objets){
            $this->addFlash('info','Aucune donnée dans le système.');
        }
        if($this->isGranted('ROLE_ADMIN_GD_FSS')){
            $grandFournisseur=$this->getUser()->getGrandFournisseur();
            return $this->redirectToRoute('app_grand_fournisseur_show', ['id'=>$grandFournisseur->getId()], Response::HTTP_SEE_OTHER);  
        }
        return $this->render('grand_fournisseur/index.html.twig', [
            'grand_fournisseurs' => $objets ,
        ]);
    }

    #[Route('/new', name: 'app_grand_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request,CompteGRCSRepository $compteGRCSRepository, GrandFournisseurRepository $grandFournisseurRepository, GRCSRepository $gRCSRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $grcs=$gRCSRepository->findOneBy([]);
        if(!$grcs){
            $this->addFlash('info','Aucun grcs dans le système. Créez-en un au préalable.');
            return $this->redirectToRoute('app_g_r_c_s_new', [], Response::HTTP_SEE_OTHER);  
        }
        $grandFournisseur = new GrandFournisseur();
        $form = $this->createForm(GrandFournisseurType::class, $grandFournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //creation d'un compte GRCS associé a ce fournisseur 
            $grcs=$gRCSRepository->findOneBy([]);
            if(!$grcs){
                $this->addFlash('warning','Aucun GRCS existe dans le système');
                return $this->redirectToRoute('app_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    
            }
            $compteGRCS=new CompteGRCS($grandFournisseur);
            $compteGRCS->setGrcs($grcs);
            $compteGRCS->setGrandFournisseur($grandFournisseur);
            $compteGRCSRepository->add($compteGRCS, true);
            // $this->addFlash('info','Un compte GRSC a été crée au même moment dans le système.');

            //fin 
            $user=$this->registerAdmGdFss($grandFournisseur, $userPasswordHasher, $entityManager);

            if(!$user){
                $this->addFlash('danger','Erreur lors de la création du compte utilisateur fournisseur associé.');
                return 0;
            }

            $grandFournisseurRepository->add($grandFournisseur, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grand_fournisseur/new.html.twig', [
            'grand_fournisseur' => $grandFournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grand_fournisseur_show', methods: ['GET'])]
    public function show(GrandFournisseur $grandFournisseur): Response
    {
        return $this->render('grand_fournisseur/show.html.twig', [
            'grand_fournisseur' => $grandFournisseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grand_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GrandFournisseur $grandFournisseur, GrandFournisseurRepository $grandFournisseurRepository): Response
    {
        $form = $this->createForm(GrandFournisseurType::class, $grandFournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grandFournisseurRepository->add($grandFournisseur, true);
            $this->addFlash('success','Opération réussie.');

            return $this->redirectToRoute('app_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grand_fournisseur/edit.html.twig', [
            'grand_fournisseur' => $grandFournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grand_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, GrandFournisseur $grandFournisseur, GrandFournisseurRepository $grandFournisseurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grandFournisseur->getId(), $request->request->get('_token'))) {
            $grandFournisseurRepository->remove($grandFournisseur, true);
            $this->addFlash('success','Opération réussie.');

        }

        return $this->redirectToRoute('app_grand_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }

    private function registerAdmGdFss(GrandFournisseur $grandFournisseur, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): User
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN_GD_FSS']);
        $uniqueGRCS = $entityManager->getRepository(GRCS::class)->findOneBy([]);
        if (!$uniqueGRCS) {
            $this->addFlash('danger', 'Aucun GRCS configuré dans le système.');
            return $this->redirectToRoute('app_accueil');
        }
        $email = $grandFournisseur->getAdresse()->getEmail();
        $user->setEmail($email);
        $user->setGrandFournisseur($grandFournisseur);
        $password=uniqid('GDFSS-');
        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        // dd('test');
        $entityManager->persist($user);
        
        $entityManager->flush();
        // dd($test);
        $this->addFlash('success', 'Vous avez créez un utilisateur admin. grand fournisseur. Login: '.$email.' Mot de passe : '.$password);
        return $user;
    }
}
