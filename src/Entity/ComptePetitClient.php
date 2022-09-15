<?php

namespace App\Entity;

use App\Repository\ComptePetitClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComptePetitClientRepository::class)]
#[ORM\Table(name:"ess_compte_petit_client")]
#[ORM\HasLifecycleCallbacks()]
class ComptePetitClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: PetitClient::class, inversedBy: 'comptePetitClients')]
    #[ORM\JoinColumn(nullable: false)]
    private $petitClient;

    #[ORM\ManyToOne(targetEntity: CompteGRCS::class, inversedBy: 'comptePetitClients')]
    #[ORM\JoinColumn(nullable: false)]
    private $compteGRCS;

    #[ORM\Column(type: 'integer')]
    private $quantiteDiesel;

    #[ORM\Column(type: 'integer')]
    private $quantiteEssence;

    #[ORM\Column(type: 'datetime')]
    private $dateDernierApprovisionnement;

    #[ORM\OneToMany(mappedBy: 'comptePetitClient', targetEntity: ActivationPostPaye::class)]
    private $activationPostPayes;

    #[ORM\OneToMany(mappedBy: 'comptePetitClient', targetEntity: Ticket::class)]
    private $tickets;

    #[ORM\OneToMany(mappedBy: 'comptePetitClient', targetEntity: ApprovisionnementPetitClient::class)]
    private $approvisionnementPetitClients;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentageDiesel = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentageEssence = null;

    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        $quantiteDiesel=$this->quantiteDiesel;
        $quantiteEssence=$this->quantiteEssence;
        $this->compteGRCS->setQuantiteDiesel($this->compteGRCS->getQuantiteDiesel()+$quantiteDiesel);
        $this->compteGRCS->setQuantiteEssence($this->compteGRCS->getQuantiteEssence()+$quantiteEssence);
    }

    public function __toString()
    {
        return $this->compteGRCS.''.$this->petitClient;
    }

    public function __construct(PetitClient $petitClient, CompteGRCS $compteGRCS)
    {
        $this->petitClient=$petitClient;
        $this->compteGRCS=$compteGRCS;
        $this->activationPostPayes = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->approvisionnementPetitClients = new ArrayCollection();
        $this->dateDernierApprovisionnement=new \DateTimeImmutable();
        $this->quantiteDiesel=0;
        $this->quantiteEssence=0;
        $this->date=new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPetitClient(): ?PetitClient
    {
        return $this->petitClient;
    }

    public function setPetitClient(?PetitClient $petitClient): self
    {
        $this->petitClient = $petitClient;

        return $this;
    }

    public function getCompteGRCS(): ?CompteGRCS
    {
        return $this->compteGRCS;
    }

    public function setCompteGRCS(?CompteGRCS $compteGRCS): self
    {
        $this->compteGRCS = $compteGRCS;

        return $this;
    }

    public function getQuantiteDiesel(): ?int
    {
        return $this->quantiteDiesel;
    }

    public function setQuantiteDiesel(int $quantiteDiesel): self
    {
        $this->quantiteDiesel = $quantiteDiesel;

        return $this;
    }

    public function getQuantiteEssence(): ?int
    {
        return $this->quantiteEssence;
    }

    public function setQuantiteEssence(int $quantiteEssence): self
    {
        $this->quantiteEssence = $quantiteEssence;

        return $this;
    }

    public function getDateDernierApprovisionnement(): ?\DateTimeInterface
    {
        return $this->dateDernierApprovisionnement;
    }

    public function setDateDernierApprovisionnement(\DateTimeInterface $dateDernierApprovisionnement): self
    {
        $this->dateDernierApprovisionnement = $dateDernierApprovisionnement;

        return $this;
    }

    /**
     * @return Collection<int, ActivationPostPaye>
     */
    public function getActivationPostPayes(): Collection
    {
        return $this->activationPostPayes;
    }

    public function addActivationPostPaye(ActivationPostPaye $activationPostPaye): self
    {
        if (!$this->activationPostPayes->contains($activationPostPaye)) {
            $this->activationPostPayes[] = $activationPostPaye;
            $activationPostPaye->setComptePetitClient($this);
        }

        return $this;
    }

    public function removeActivationPostPaye(ActivationPostPaye $activationPostPaye): self
    {
        if ($this->activationPostPayes->removeElement($activationPostPaye)) {
            // set the owning side to null (unless already changed)
            if ($activationPostPaye->getComptePetitClient() === $this) {
                $activationPostPaye->setComptePetitClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setComptePetitClient($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getComptePetitClient() === $this) {
                $ticket->setComptePetitClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApprovisionnementPetitClient>
     */
    public function getApprovisionnementPetitClients(): Collection
    {
        return $this->approvisionnementPetitClients;
    }

    public function addApprovisionnementPetitClient(ApprovisionnementPetitClient $approvisionnementPetitClient): self
    {
        if (!$this->approvisionnementPetitClients->contains($approvisionnementPetitClient)) {
            $this->approvisionnementPetitClients[] = $approvisionnementPetitClient;
            $approvisionnementPetitClient->setComptePetitClient($this);
        }

        return $this;
    }

    public function removeApprovisionnementPetitClient(ApprovisionnementPetitClient $approvisionnementPetitClient): self
    {
        if ($this->approvisionnementPetitClients->removeElement($approvisionnementPetitClient)) {
            // set the owning side to null (unless already changed)
            if ($approvisionnementPetitClient->getComptePetitClient() === $this) {
                $approvisionnementPetitClient->setComptePetitClient(null);
            }
        }

        return $this;
    }

    public function getPourcentageDiesel(): ?float
    {
        return $this->pourcentageDiesel;
    }

    public function setPourcentageDiesel(?float $pourcentageDiesel): self
    {
        $this->pourcentageDiesel = $pourcentageDiesel;

        return $this;
    }

    public function getPourcentageEssence(): ?float
    {
        return $this->pourcentageEssence;
    }

    public function setPourcentageEssence(?float $pourcentageEssence): self
    {
        $this->pourcentageEssence = $pourcentageEssence;

        return $this;
    }
}
