<?php

namespace App\Controller;

use App\Entity\ComptePetitClient;
use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\ComptePetitClientRepository;
use App\Repository\GrandFournisseurRepository;
use App\Repository\PetitClientRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN_PT_CL')) {
            $petitClient = $this->getUser()->getPetitClient();
            $comptePetitClient = $comptePetitClientRepository->findOneBy(['petitClient' => $petitClient]);
            
            $objets = $ticketRepository->findBy(['comptePetitClient' => $comptePetitClient],['createdAt'=>'DESC','isServi'=>'ASC']);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }

            return $this->render('ticket/index.html.twig', [
                'tickets' => $ticketRepository->findAll(),
            ]);
        }
        // dd('jambo');
        $objets = $ticketRepository->findBy([],['createdAt'=>'DESC','isServi'=>'ASC']);
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $objets,
        ]);
    }

    #[Route('/servie', name: 'app_ticket_servie_index', methods: ['GET'])]
    public function indexServie(TicketRepository $ticketRepository, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN_PT_CL')) {
            $petitClient = $this->getUser()->getPetitClient();
            $comptePetitClient = $comptePetitClientRepository->findOneBy(['petitClient' => $petitClient]);
            
            $objets = $ticketRepository->findBy(['comptePetitClient' => $comptePetitClient,'isServi'=>true],['createdAt'=>'DESC','isServi'=>'ASC']);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }

            return $this->render('ticket/index.html.twig', [
                'tickets' => $objets,
            ]);
        }
        // dd('jambo');
        $objets = $ticketRepository->findBy(['isServi'=>true],['createdAt'=>'DESC','isServi'=>'ASC']);
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $objets,
        ]);
    }

    
    #[Route('/nonServie', name: 'app_ticket_non_servie_index', methods: ['GET'])]
    public function indexNonServie(TicketRepository $ticketRepository, ComptePetitClientRepository $comptePetitClientRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN_PT_CL')) {
            $petitClient = $this->getUser()->getPetitClient();
            $comptePetitClient = $comptePetitClientRepository->findOneBy(['petitClient' => $petitClient]);
            
            $objets = $ticketRepository->findBy(['comptePetitClient' => $comptePetitClient,'isServi'=>false],['createdAt'=>'DESC','isServi'=>'ASC']);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }

            return $this->render('ticket/index.html.twig', [
                'tickets' => $objets,
            ]);
        }
        // dd('jambo');
        $objets = $ticketRepository->findBy(['isServi'=>false],['createdAt'=>'DESC','isServi'=>'ASC']);
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $objets,
        ]);
    }

    #[Route('/new/{id}', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GrandFournisseurRepository $grandFournisseurRepository, ComptePetitClient $comptePetitClient, TicketRepository $ticketRepository): Response
    {
        $ticket = new Ticket($comptePetitClient);
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        $user = $this->getUser();
        $ticket->setUtilisateur($user);
        if ($form->isSubmitted() && $form->isValid()) {
            //mise a jour du compte fsseur correspondant dans l'entité 
            // ajouter une valeur dans le ticket isDiesel afin de differencier et mieux caclculer 
            $quantite = $ticket->getQuantite();
            $quantiteDiesel = $comptePetitClient->getQuantiteDiesel();
            $quantiteEssence = $comptePetitClient->getQuantiteEssence();
            if ($quantite > $quantiteDiesel) {
                $this->addFlash('info', 'Attention');
            } elseif ($quantite > $quantiteDiesel) {
                $this->addFlash('info', 'Attention');
            }

            $fournisseur = $comptePetitClient->getCompteGRCS()->getGrandFournisseur();


            $message = new Message();
            $contenu = "Bonjour, Nous tenons à vous informé ce jour que la création du ticket " . $ticket . " a eu lieux. Cordialement.";
            $message->setSujet('Création du ticket')->setContenu($contenu);
            $fournisseur->addMessage($message);
            $grandFournisseurRepository->add($fournisseur, true);

            $ticketRepository->add($ticket, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->add($ticket, true);
            $this->addFlash('success', 'Opération réussie.');

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/servi', name: 'app_ticket_servi', methods: ['GET', 'POST'])]
    public function servi(Request $request, PetitClientRepository $petitClientRepository, Ticket $ticket, TicketRepository $ticketRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $ticket->setIsServi(true);
        $ticket->setDateRetrait(new \DateTimeImmutable());
        $ticketRepository->add($ticket, true);

        //mise a jour de la quantité du compte client 

        $comptePetitClient = $ticket->getComptePetitClient();
        $compteGRCS=$ticket->getComptePetitClient()->getCompteGRCS();

        $quantite = $ticket->getQuantite();

        if ($ticket->getTypeCarburant() == "Diesel") {
            $comptePetitClient->setQuantiteDiesel($comptePetitClient->getQuantiteDiesel() - $quantite);
            $compteGRCS->setQteDieselNonServie($compteGRCS->getQteDieselNonServie()-$quantite);
        } elseif ($ticket->getTypeCarburant() == "Essence") {
            $comptePetitClient->setQuantiteEssence($comptePetitClient->getQuantiteEssence() - $quantite);
            $compteGRCS->setQteEssenceNonServie($compteGRCS->getQteEssenceNonServie()-$quantite);

        } else {
            $this->addFlash('danger', 'Une erreur a été detecté dans les données.');
            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }
        //fin mise a jour du compte client 

        $petitClient = $comptePetitClient->getPetitClient();
        // $comptePetitClient->setQuantiteDiesel()
        $message = new Message();
        $contenu = "Bonjour, Nous tenons à vous informé ce jour que le ticket " . $ticket . " est servi. Cordialement.";
        $message->setSujet('Ticket servi')->setContenu($contenu);
        $petitClient->addMessage($message);
        $petitClientRepository->add($petitClient, true);

        //enregistrer le compte grcs avec les maj de qte non servie 
        $entityManagerInterface->persist($compteGRCS);
        $entityManagerInterface->flush();

        

        $this->addFlash('success', 'Opération réussie. Le ticket passe en état servi.');

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $ticketRepository->remove($ticket, true);
            $this->addFlash('success', 'Opération réussie.');
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
}
