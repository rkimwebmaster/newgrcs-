<?php

namespace App\Entity;

use App\Repository\PetitClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PetitClientRepository::class)]
#[ORM\Table(name:"ess_petit_client")]
#[ORM\HasLifecycleCallbacks()]
class PetitClient
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

    #[ORM\OneToMany(mappedBy: 'petitClient', targetEntity: ComptePetitClient::class)]
    private $comptePetitClients;

    #[ORM\ManyToMany(targetEntity: MessagePetitClient::class, mappedBy: 'petitClient')]
    private $messagePetitClients;

    #[ORM\OneToMany(mappedBy: 'petitClient', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column]
    private ?int $nombreMessage = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Entreprise $entreprise = null;

    #[ORM\Column]
    private ?bool $isParticulier = false;

    #[ORM\OneToMany(mappedBy: 'destinataireClient', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    #[ORM\Column(nullable: true)]
    private ?int $qteStockRecommandeDiesel = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteStockRecommandeEssence = null;

    
    public function incrementerMessage(){
        $this->setNombreMessage($this->getNombreMessage()+1);
    }

    public function reinitialiserMessage(){
        $this->setNombreMessage(0);
    }
    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        
        if($this->entreprise==null){
            $this->isParticulier=true;
        }else{
            $this->isParticulier=false;
        }
    }

    public function __toString()
    {
        // return $this->entreprise;
        if($this->entreprise){
            return $this->entreprise;
        }else{
            return $this->identite.'*** Particulier';
        }
    }

    public function __construct()
    {
        $this->comptePetitClients = new ArrayCollection();
        $this->messageGRCS = new ArrayCollection();
        $this->messagePetitClients = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->nombreMessage=0;
        $this->isParticulier=false;
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

    /**
     * @return Collection<int, ComptePetitClient>
     */
    public function getComptePetitClients(): Collection
    {
        return $this->comptePetitClients;
    }

    public function addComptePetitClient(ComptePetitClient $comptePetitClient): self
    {
        if (!$this->comptePetitClients->contains($comptePetitClient)) {
            $this->comptePetitClients[] = $comptePetitClient;
            $comptePetitClient->setPetitClient($this);
        }

        return $this;
    }

    public function removeComptePetitClient(ComptePetitClient $comptePetitClient): self
    {
        if ($this->comptePetitClients->removeElement($comptePetitClient)) {
            // set the owning side to null (unless already changed)
            if ($comptePetitClient->getPetitClient() === $this) {
                $comptePetitClient->setPetitClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessagePetitClient>
     */
    public function getMessagePetitClients(): Collection
    {
        return $this->messagePetitClients;
    }

    public function addMessagePetitClient(MessagePetitClient $messagePetitClient): self
    {
        if (!$this->messagePetitClients->contains($messagePetitClient)) {
            $this->messagePetitClients[] = $messagePetitClient;
            $messagePetitClient->addPetitClient($this);
        }

        return $this;
    }

    public function removeMessagePetitClient(MessagePetitClient $messagePetitClient): self
    {
        if ($this->messagePetitClients->removeElement($messagePetitClient)) {
            $messagePetitClient->removePetitClient($this);
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
            $user->setPetitClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPetitClient() === $this) {
                $user->setPetitClient(null);
            }
        }

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

    public function isIsParticulier(): ?bool
    {
        return $this->isParticulier;
    }

    public function setIsParticulier(bool $isParticulier): self
    {
        $this->isParticulier = $isParticulier;

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
            $message->setDestinataireClient($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDestinataireClient() === $this) {
                $message->setDestinataireClient(null);
            }
        }

        return $this;
    }

    public function getQteStockRecommandeDiesel(): ?int
    {
        return $this->qteStockRecommandeDiesel;
    }

    public function setQteStockRecommandeDiesel(?int $qteStockRecommandeDiesel): self
    {
        $this->qteStockRecommandeDiesel = $qteStockRecommandeDiesel;

        return $this;
    }

    public function getQteStockRecommandeEssence(): ?int
    {
        return $this->qteStockRecommandeEssence;
    }

    public function setQteStockRecommandeEssence(?int $qteStockRecommandeEssence): self
    {
        $this->qteStockRecommandeEssence = $qteStockRecommandeEssence;

        return $this;
    }
}
