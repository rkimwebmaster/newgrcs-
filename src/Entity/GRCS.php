<?php

namespace App\Entity;

use App\Repository\GRCSRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GRCSRepository::class)]
#[ORM\Table(name:"ess_grcs")]
class GRCS
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

    #[ORM\OneToMany(mappedBy: 'grcs', targetEntity: CompteGRCS::class)]
    private $compteGRCS;

    #[ORM\OneToMany(mappedBy: 'grcs', targetEntity: MessageGRCS::class)]
    private $messageGRCS;

    #[ORM\OneToMany(mappedBy: 'grcs', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column]
    private ?int $nombreMessage = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Entreprise $entreprise = null;

    #[ORM\Column]
    private ?int $nombreNotification = null;

    #[ORM\Column]
    private ?float $prixEssence = null;

    #[ORM\Column]
    private ?float $prixDiesel = null;

    #[ORM\Column(length: 255)]
    private ?string $monnaie = null;

    #[ORM\OneToMany(mappedBy: 'destinataireGRCS', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    #[ORM\Column(nullable: true)]
    private ?int $qteStockRecommandeDiesel = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteStockRecommandeEssence = null;
    
    public function __toString()
    {
        return $this->entreprise;
    }

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

    public function __construct()
    {
        $this->messagePetitClients = new ArrayCollection();
        $this->messageGrandFournisseurs = new ArrayCollection();
        $this->compteGRCS = new ArrayCollection();
        $this->messageGRCS = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->nombreMessage=0;
        $this->nombreNotification=0;
        $this->prixDiesel=0;
        $this->prixEssence=0;
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
     * @return Collection<int, CompteGRCS>
     */
    public function getCompteGRCS(): Collection
    {
        return $this->compteGRCS;
    }

    public function addCompteGRC(CompteGRCS $compteGRC): self
    {
        if (!$this->compteGRCS->contains($compteGRC)) {
            $this->compteGRCS[] = $compteGRC;
            $compteGRC->setGrcs($this);
        }

        return $this;
    }

    public function removeCompteGRC(CompteGRCS $compteGRC): self
    {
        if ($this->compteGRCS->removeElement($compteGRC)) {
            // set the owning side to null (unless already changed)
            if ($compteGRC->getGrcs() === $this) {
                $compteGRC->setGrcs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MessageGRCS>
     */
    public function getMessageGRCS(): Collection
    {
        return $this->messageGRCS;
    }

    public function addMessageGRC(MessageGRCS $messageGRC): self
    {
        if (!$this->messageGRCS->contains($messageGRC)) {
            $this->messageGRCS[] = $messageGRC;
            $messageGRC->setGrcs($this);
        }

        return $this;
    }

    public function removeMessageGRC(MessageGRCS $messageGRC): self
    {
        if ($this->messageGRCS->removeElement($messageGRC)) {
            // set the owning side to null (unless already changed)
            if ($messageGRC->getGrcs() === $this) {
                $messageGRC->setGrcs(null);
            }
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
            $user->setGrcs($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGrcs() === $this) {
                $user->setGrcs(null);
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

    public function getPrixEssence(): ?float
    {
        return $this->prixEssence;
    }

    public function setPrixEssence(float $prixEssence): self
    {
        $this->prixEssence = $prixEssence;

        return $this;
    }

    public function getPrixDiesel(): ?float
    {
        return $this->prixDiesel;
    }

    public function setPrixDiesel(float $prixDiesel): self
    {
        $this->prixDiesel = $prixDiesel;

        return $this;
    }

    public function getMonnaie(): ?string
    {
        return $this->monnaie;
    }

    public function setMonnaie(string $monnaie): self
    {
        $this->monnaie = $monnaie;

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
            $message->setDestinataireGRCS($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDestinataireGRCS() === $this) {
                $message->setDestinataireGRCS(null);
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
