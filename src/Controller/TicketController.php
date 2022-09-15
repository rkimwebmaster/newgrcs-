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
            $objets = $ticketRepository->findBy(['comptePetitClient' => $comptePetitClient]);
            if (!$objets) {
                $this->addFlash('info', 'Aucune donnée dans le système.');
            }

            return $this->render('ticket/index.html.twig', [
                'tickets' => $ticketRepository->findAll(),
            ]);
        }
        $objets = $ticketRepository->findAll();
        if (!$objets) {
            $this->addFlash('info', 'Aucune donnée dans le système.');
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
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
            $contenu = "Bonjour, Nous tenons à vous informé ce jour que la création du ticket ".$ticket." a eu lieux. Cordialement.";
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
    public function servi(Request $request, PetitClientRepository $petitClientRepository, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $ticket->setIsServi(true);
        $ticketRepository->add($ticket, true);

        
        $petitClient = $ticket->getComptePetitClient()->getPetitClient();
        $message = new Message();
        $contenu = "Bonjour, Nous tenons à vous informé ce jour que le ticket ".$ticket." est servi. Cordialement.";
        $message->setSujet('Ticket servi')->setContenu($contenu);
        $petitClient->addMessage($message);
        $petitClientRepository->add($petitClient, true);

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
