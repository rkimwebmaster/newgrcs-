<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserChangePasswordAdminType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
// #[IsGranted("IS_AUTHENTICATED_FULLY")]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN_GD_FSS')) {
            $structure = $this->getUser()->getGrandFournisseur();
            $objets = $userRepository->findBy(['grandFournisseur' => $structure]);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }
        }elseif($this->isGranted('ROLE_ADMIN_PT_CL')){
            $structure = $this->getUser()->getPetitClient();
            // dd($structure);
            $objets = $userRepository->findBy(['petitClient' => $structure]);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }
        }else{
            $objets = $userRepository->findAll();
        }
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }
        return $this->render('user/index.html.twig', [
            'users' => $objets,
        ]);
    }

    #[Route('/{id}', name: 'app_profile', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/changePass", name="user_change_pass", methods={"GET","POST"})
     */
    public function changePass(Request $request, User $user, UserPasswordHasherInterface $passwordEncoder, UserRepository $userRepository): Response
    {
    }

    /**
     * @Route("/{id}/changePassAdmin", name="user_change_pass_admin", methods={"GET","POST"})
     */
    public function changePassAdmin(Request $request, EntityManagerInterface $entityManagerInterface, User $user, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $user;
        $form = $this->createForm(UserChangePasswordAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $entityManagerInterface;;
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. Vous avez changer le mot de passe de ' . $user . '.');
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/changePasswordAdmin.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }



    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
