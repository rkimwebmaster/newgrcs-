<?php

namespace App\Controller;

use App\Entity\GRCS;
use App\Entity\User;
use App\Form\GRCSType;
use App\Form\PrixCarburantType;
use App\Repository\GRCSRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grcs')]
class GRCSController extends AbstractController
{
    #[Route('/', name: 'app_g_r_c_s_index', methods: ['GET'])]
    public function index(GRCSRepository $gRCSRepository): Response
    {
        $checkGRCS = $gRCSRepository->findOneBy([]);
        if ($checkGRCS) {
            $this->addFlash('info', 'Ci-dessous infos GRCS');
            return $this->redirectToRoute('app_g_r_c_s_show', ['id' => $checkGRCS->getId()], Response::HTTP_SEE_OTHER);
        }else{
            $this->addFlash('info', 'Ci-dessous créer un compte GRCS');
            return $this->redirectToRoute('app_g_r_c_s_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('grcs/index.html.twig', [
            'g_r_cs' => $gRCSRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_g_r_c_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GRCSRepository $gRCSRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $checkGRCS = $gRCSRepository->findOneBy([]);
        if ($checkGRCS) {
            $this->addFlash('info', 'Ci-dessous infos GRCS');
            return $this->redirectToRoute('app_g_r_c_s_show', ['id' => $checkGRCS->getId()], Response::HTTP_SEE_OTHER);
        }
        $gRC = new GRCS();
        $form = $this->createForm(GRCSType::class, $gRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gRCSRepository->add($gRC, true);
            // $this->addFlash('success', 'Opération réussie.');
            ///creation du user correspondant 
            $user=$this->registerAdmGRCS($gRC, $userPasswordHasher, $entityManager);
            //
            return $this->redirectToRoute('app_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grcs/new.html.twig', [
            'g_r_c' => $gRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_g_r_c_s_show', methods: ['GET'])]
    public function show(GRCS $gRC): Response
    {
        return $this->render('grcs/show.html.twig', [
            'g_r_c' => $gRC,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_g_r_c_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GRCS $gRC, GRCSRepository $gRCSRepository): Response
    {
        $form = $this->createForm(GRCSType::class, $gRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gRCSRepository->add($gRC, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grcs/edit.html.twig', [
            'g_r_c' => $gRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/carburant', name: 'app_g_r_c_s_carburant', methods: ['GET', 'POST'])]
    public function carburant(Request $request, GRCS $gRC, GRCSRepository $gRCSRepository): Response
    {
        $form = $this->createForm(PrixCarburantType::class, $gRC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gRCSRepository->add($gRC, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grcs/edit.html.twig', [
            'g_r_c' => $gRC,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_g_r_c_s_delete', methods: ['POST'])]
    public function delete(Request $request, GRCS $gRC, GRCSRepository $gRCSRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $gRC->getId(), $request->request->get('_token'))) {
            $gRCSRepository->remove($gRC, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_g_r_c_s_index', [], Response::HTTP_SEE_OTHER);
    }

    private function registerAdmGRCS(GRCS $gRCS, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): User
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN_GRCS']);
        $uniqueGRCS = $entityManager->getRepository(GRCS::class)->findOneBy([]);
        if (!$uniqueGRCS) {
            $this->addFlash('danger', 'Aucun GRCS configuré dans le système.');
            return $this->redirectToRoute('app_accueil');
        }
        $email = $uniqueGRCS->getAdresse()->getEmail();
        $user->setEmail($email);
        $user->setGrcs($gRCS);
        $password=uniqid('GCRS-');
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
        $this->addFlash('success', 'Vous avez créez un utilisateur admin. GRCS. Login: '.$email.' Mot de passe : '.$password);
        return $user;
    }
}
