<?php

namespace App\Entity;

use App\Repository\GrandFournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrandFournisseurRepository::class)]
#[ORM\Table(name:"ess_grand_fournisseur")]
class GrandFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: Adresse::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $adresse;

    #[ORM\OneToOne(targetEntity: Identite::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $identite;

    #[ORM\OneToOne(mappedBy: 'grandFournisseur', targetEntity: CompteGRCS::class, cascade: ['persist', 'remove'])]
    private $compteGRCS;

    #[ORM\ManyToMany(targetEntity: MessageGrandFournisseur::class, mappedBy: 'grandFournisseurs')]
    private $messageGrandFournisseurs;

    #[ORM\OneToMany(mappedBy: 'grandFournisseur', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column]
    private ?int $nombreMessage = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreNotification;

    #[ORM\OneToMany(mappedBy: 'destinataireFournisseur', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    
    public function incrementerNotification(){
        $this->setNombreNotification($this->getNombreNotification()+1);
    }

    public function reinitialiserNotification(){
        $this->setNombreNotification(0);
    }

    public function incrementerMessage(){
        $this->setNombreMessage($this->getNombreMessage()+1);
    }

    public function reinitialiserMessage(){
        $this->setNombreMessage(0);
    }
    
    public function __toString()
    {
        return $this->entreprise;
    }

    public function __construct()
    {
        $this->messageGrandFournisseurs = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->nombreMessage=0;
        $this->nombreNotification=0;
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdentite(): ?Identite
    {
        return $this->identite;
    }

    public function setIdentite(Identite $identite): self
    {
        $this->identite = $identite;

        return $this;
    }

    public function getCompteGRCS(): ?CompteGRCS
    {
        return $this->compteGRCS;
    }

    public function setCompteGRCS(CompteGRCS $compteGRCS): self
    {
        // set the owning side of the relation if necessary
        if ($compteGRCS->getGrandFournisseur() !== $this) {
            $compteGRCS->setGrandFournisseur($this);
        }

        $this->compteGRCS = $compteGRCS;

        return $this;
    }
    /**
     * @return Collection<int, MessageGrandFournisseur>
     */
    public function getMessageGrandFournisseurs(): Collection
    {
        return $this->messageGrandFournisseurs;
    }

    public function addMessageGrandFournisseur(MessageGrandFournisseur $messageGrandFournisseur): self
    {
        if (!$this->messageGrandFournisseurs->contains($messageGrandFournisseur)) {
            $this->messageGrandFournisseurs[] = $messageGrandFournisseur;
            $messageGrandFournisseur->addGrandFournisseur($this);
        }

        return $this;
    }

    public function removeMessageGrandFournisseur(MessageGrandFournisseur $messageGrandFournisseur): self
    {
        if ($this->messageGrandFournisseurs->removeElement($messageGrandFournisseur)) {
            $messageGrandFournisseur->removeGrandFournisseur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setGrandFournisseur($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGrandFournisseur() === $this) {
                $user->setGrandFournisseur(null);
            }
        }

        return $this;
    }

    public function getNombreMessage(): ?int
    {
        return $this->nombreMessage;
    }

    public function setNombreMessage(int $nombreMessage): self
    {
        $this->nombreMessage = $nombreMessage;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getNombreNotification(): ?int
    {
        return $this->nombreNotification;
    }

    public function setNombreNotification(int $nombreNotification): self
    {
        $this->nombreNotification = $nombreNotification;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setDestinataireFournisseur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDestinataireFournisseur() === $this) {
                $message->setDestinataireFournisseur(null);
            }
        }

        return $this;
    }

}
