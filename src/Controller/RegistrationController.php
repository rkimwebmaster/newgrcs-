<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GRCS;
use App\Form\RegistrationFormType;
use App\Form\RegistrationGdFssFormType;
use App\Form\RegistrationPtClFormType;
use App\Repository\GrandFournisseurRepository;
use App\Repository\GRCSRepository;
use App\Repository\PetitClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class RegistrationController extends AbstractController
{

    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request,GRCSRepository $gRCSRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ///affecter l'entreprise a l utilisateur simple 
            if($this->isGranted('ROLE_ADMIN_GRCS')){
                $uniqueGRCS=$gRCSRepository->findOneBy([]);
                $user->setGrcs($uniqueGRCS);
                $user->setRoles(['ROLE_USER_GRCS']);
                
            }elseif($this->isGranted('ROLE_ADMIN_GD_FSS')){
                $grandFournisseur=$user->getGrandFournisseur();
                $user->setGrandFournisseur($grandFournisseur);
                $user->setRoles(['ROLE_USER_GD_FSS']);

            }elseif($this->isGranted('ROLE_ADMIN_PT_CL')){
                $petitClient=$user->getPetitClient();
                $user->setPetitClient($petitClient);
                $user->setRoles(['ROLE_USER_PT_CL']);
            }else{
                $this->addFlash('warning','Vous ne pouvez créez ce type d\'utilisateur. Rassurez-vous d\'être connecté en plus du privillège admin necessaire. ');
                return $this->redirectToRoute('app_accueil');
            }
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Opération réussie. Vous avez créez un utilisateur.');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/registerAdm', name: 'app_register_adm')]
    public function registerAdm(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_ADMIN']);
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Opération réussie. Vous avez créez '.$user.' comme administrateur.');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }



    #[Route('/registerAdmGRCS', name: 'app_register_adm_grcs')]
    public function registerAdmGRCS(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             /** @var UploadedFile $brochureFile */
             $brochureFile = $form->get('brochure')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($brochureFile) {
                 $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $this->slugger->slug($originalFilename);
                 $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                     $brochureFile->move(
                         $this->getParameter('profile_pictures_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
 
                 // updates the 'brochureFilename' property to store the PDF file name
                 // instead of its contents
                 $user->setPhoto($newFilename);
             }

            $user->setRoles(['ROLE_ADMIN_GRCS']);
            
            $uniqueGRCS=$entityManager->getRepository(GRCS::class)->findOneBy([]);
            if(!$uniqueGRCS){
                $this->addFlash('danger','Aucun GRCS configuré dans le système.');
                return $this->redirectToRoute('app_accueil');

            }
            $user->setGrcs($uniqueGRCS);
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Opération réussie. Vous avez créez '.$user.' comme utilisateur admin. GRCS.');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/registerAdmGdFss', name: 'app_register_adm_gdfss')]
    public function registerAdmGdFss(Request $request, GrandFournisseurRepository $grandFournisseurRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $checkGrandFournisseur=$grandFournisseurRepository->findOneBy([]);
        if(!$checkGrandFournisseur){
            $this->addFlash('danger','aucun grand fournisseur enregistré dans le système. Créez-en un au préalable.');
            return $this->redirectToRoute('app_grand_fournisseur_new');
        }
        $user = new User();
        $form = $this->createForm(RegistrationGdFssFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhoto($newFilename);
            }
            $user->setRoles(['ROLE_ADMIN_GD_FSS']);

            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Opération réussie. Vous avez créez '.$user.' comme utilisateur admi. pour grand fousrnisseur.');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

   
    #[Route('/registerAdmPcl', name: 'app_register_adm_pcl')]
    public function registerAdmPcl(Request $request, PetitClientRepository $petitClientRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $checkPetitClient=$petitClientRepository->findOneBy([]);
        if(!$checkPetitClient){
            $this->addFlash('danger','aucun petit client enregistré dans le système. Créez-en un au préalable.');
            return $this->redirectToRoute('app_petit_client_new');
        }
        $user = new User();
        $form = $this->createForm(RegistrationPtClFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setPhoto($newFilename);
            }
            $user->setRoles(['ROLE_ADMIN_PT_CL']);

            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Opération réussie. Vous avez créez '.$user.' admin pour petit client.');
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}
