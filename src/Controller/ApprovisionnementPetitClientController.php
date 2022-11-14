<?php

namespace App\Controller;

use App\Entity\ApprovisionnementPetitClient;
use App\Entity\ComptePetitClient;
use App\Entity\Message;
use App\Entity\PetitClient;
use App\Form\ApprovisionnementPetitClientType;
use App\Repository\ApprovisionnementPetitClientRepository;
use App\Repository\CompteGRCSRepository;
use App\Repository\ComptePetitClientRepository;
use App\Repository\PetitClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/approvisionnementpetitclient')]
class ApprovisionnementPetitClientController extends AbstractController
{
    #[Route('/', name: 'app_approvisionnement_petit_client_index', methods: ['GET'])]
    public function index(CompteGRCSRepository $compteGRCSRepository, ComptePetitClientRepository $comptePetitClientRepository, ApprovisionnementPetitClientRepository $approvisionnementPetitClientRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN_PT_CL')) {
            $comptePetitClient = $this->getUser()->getPetitClient();
            $objets = $approvisionnementPetitClientRepository->findBy(['comptePetitClient' => $comptePetitClient]);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }
            return $this->render('approvisionnement_petit_client/index.html.twig', [
                'approvisionnement_petit_clients' => $approvisionnementPetitClientRepository->findAll(),
            ]);
        }
        // $comptePetitClients=$comptePetitClientRepository->findBy(['petitClient'=>$petitClient]);
        $objets = $approvisionnementPetitClientRepository->findAll();
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }
        if ($this->isGranted('ROLE_ADMIN_GD_FSS')) {
            $grandFournisseur = $this->getUser()->getGrandFournisseur();

            // dd('salut');
            $compteGRCS = $compteGRCSRepository->findOneBy(['grandFournisseur' => $grandFournisseur]);
            $comptePetitClient = $comptePetitClientRepository->findOneBy(['compteGRCS' => $compteGRCS]);

            $approvisionnementPetitClients = $approvisionnementPetitClientRepository->findBy(['comptePetitClient' => $comptePetitClient]);

            return $this->render('approvisionnement_petit_client/index.html.twig', [
                'approvisionnement_petit_clients' => $approvisionnementPetitClients,
            ]);
        }
        $appros=$approvisionnementPetitClientRepository->findAll();
        // dd($appros);
        return $this->render('approvisionnement_petit_client/index.html.twig', [
            'approvisionnement_petit_clients' => $appros,
        ]);
    }


    #[Route('/indexPtCl/{id}', name: 'app_approvisionnement_petit_client_unique_index', methods: ['GET'])]
    public function indexPtCl(PetitClient $petitClient, ComptePetitClientRepository $comptePetitClientRepository, ApprovisionnementPetitClientRepository $approvisionnementPetitClientRepository): Response
    {
        $comptePetitClients = $comptePetitClientRepository->findBy(['petitClient' => $petitClient]);
        $objets = $approvisionnementPetitClientRepository->findAll();
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }
        return $this->render('approvisionnement_petit_client/index.html.twig', [
            'approvisionnement_petit_clients' => $approvisionnementPetitClientRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_approvisionnement_petit_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request,PetitClientRepository $petitClientRepository, ComptePetitClient $comptePetitClient, ApprovisionnementPetitClientRepository $approvisionnementPetitClientRepository, SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_ADMIN_GRCS')) {
            $this->addFlash('info', 'Vous ne pouvez acceder à cette fonctionnalité.');
            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }
        $approvisionnementPetitClient = new ApprovisionnementPetitClient($comptePetitClient, true);
        $user = $this->getUser();
        $approvisionnementPetitClient->setUtilisateur($user);
        $form = $this->createForm(ApprovisionnementPetitClientType::class, $approvisionnementPetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
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
                $approvisionnementPetitClient->setBordereau($newFilename);
            }

            $compteGRCS = $comptePetitClient->getCompteGRCS();
            $qteDiesel = $approvisionnementPetitClient->getQuantiteDiesel();
            $qteEssence = $approvisionnementPetitClient->getQuantiteEssence();
            $qteDieselGRCS = $compteGRCS->getQuantiteDiesel();
            $qteEssenceGRCS = $compteGRCS->getQuantiteEssence();
            //verifier la quantité est compatible 
            if ($qteDiesel > $qteDieselGRCS and $qteEssence > $qteEssenceGRCS and $compteGRCS->isIsPostPaye()==false) {
                $this->addFlash('warning', 'Le compte GRCS n\'est pas postpayé. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($qteDiesel > $qteDieselGRCS and $compteGRCS->isIsPostPaye() ==false) {
                $this->addFlash('warning', 'La quantité diesel est supérieure à celle en stock. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($qteEssence > $qteEssenceGRCS and $compteGRCS->isIsPostPaye()==false) {
                $this->addFlash('warning', 'La quantité essence est supérieure à celle en stock. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
            }

            if($compteGRCS->isIsPostPaye()){
                $activations=$compteGRCS->getActivationPostPayeGRCS();
                foreach($activations as $activation){
                    if($activation->isIsCloture()){
                        $qteDieselAutorise=$activation->getQuantiteMaxDieselAutorise();
                        $qteEssenceAutorise=$activation->getQuantiteMaxEssenceAutorise();
                        if ($qteDiesel > $qteDieselAutorise and $qteEssence > $qteEssenceAutorise) {
                            $this->addFlash('warning', 'Les 2 quantités surpasse la quantité autorisée. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
                        } elseif ($qteDiesel > $qteDieselAutorise ) {
                            $this->addFlash('warning', 'La quantité surpasse la quantité autorisée. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
                        } elseif ($qteEssence > $qteEssenceAutorise ) {
                            $this->addFlash('warning', 'La quantité surpasse la quantité autorisée. Contacter le fournisseur ' . $approvisionnementPetitClient->getComptePetitClient()->getCompteGRCS()->getGrandFournisseur() . ' pour réapprovisionnement.');
                            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
                        }
                    }
                }
                $this->addFlash('info', 'Le compte '.$compteGRCS.' est en mode postpayé.');
            }

            ///notifier le petit client 
            //creation du message de notofication 
            $petitClient=$comptePetitClient->getPetitClient();
            $message=new Message();
            $contenu="Bonjour, Nous ténons à vous informé ce jour que le compte ".$comptePetitClient." a été approvisionné. Cordialement.";
            $message->setSujet('Compte approvisionné')->setContenu($contenu);
            $petitClient->addMessage($message);
            //fin 
            $petitClientRepository->add($petitClient, true);
            $approvisionnementPetitClientRepository->add($approvisionnementPetitClient, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_petit_client_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_approvisionnement_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('approvisionnement_petit_client/new.html.twig', [
            'approvisionnement_petit_client' => $approvisionnementPetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_approvisionnement_petit_client_show', methods: ['GET'])]
    public function show(ApprovisionnementPetitClient $approvisionnementPetitClient): Response
    {
        return $this->render('approvisionnement_petit_client/show.html.twig', [
            'approvisionnement_petit_client' => $approvisionnementPetitClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_approvisionnement_petit_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApprovisionnementPetitClient $approvisionnementPetitClient, ApprovisionnementPetitClientRepository $approvisionnementPetitClientRepository): Response
    {
        $form = $this->createForm(ApprovisionnementPetitClientType::class, $approvisionnementPetitClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $approvisionnementPetitClientRepository->add($approvisionnementPetitClient, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_approvisionnement_petit_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('approvisionnement_petit_client/edit.html.twig', [
            'approvisionnement_petit_client' => $approvisionnementPetitClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_approvisionnement_petit_client_delete', methods: ['POST'])]
    public function delete(Request $request, ApprovisionnementPetitClient $approvisionnementPetitClient, ApprovisionnementPetitClientRepository $approvisionnementPetitClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $approvisionnementPetitClient->getId(), $request->request->get('_token'))) {
            $approvisionnementPetitClientRepository->remove($approvisionnementPetitClient, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_approvisionnement_petit_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
