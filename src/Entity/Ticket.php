<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name:"ess_ticket")]
#[ORM\HasLifecycleCallbacks()]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateur;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nomChauffeur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $numeroPlaqueImmatriculation;

    #[ORM\Column(type: 'boolean')]
    private $isGroupeElectrogene;

    #[ORM\Column(type: 'datetime')]
    private $dateRetrait;

    #[ORM\Column(type: 'integer')]
    private $quantite;

    #[ORM\Column(type: 'boolean')]
    private $isCredit;

    #[ORM\Column(type: 'float')]
    private $totalMontant;

    #[ORM\Column(type: 'boolean')]
    private $isServi;

    #[ORM\ManyToOne(targetEntity: ComptePetitClient::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private $comptePetitClient;

    #[ORM\Column(length: 255, nullable:false)]
    private ?string $typeCarburant = null;

    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        $this->comptePetitClient->getCompteGRCS()->getGrandFournisseur()->incrementerNotification();
    }

    public function __construct(ComptePetitClient $comptePetitClient){
        $this->comptePetitClient=$comptePetitClient;
        $this->date=new \DateTimeImmutable();
        $this->dateRetrait=new \DateTime("+2 days");
        $this->isCredit=false;
        $this->totalMontant=00;
        $this->isServi=false;
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNomChauffeur(): ?string
    {
        return $this->nomChauffeur;
    }

    public function setNomChauffeur(?string $nomChauffeur): self
    {
        $this->nomChauffeur = $nomChauffeur;

        return $this;
    }

    public function getNumeroPlaqueImmatriculation(): ?string
    {
        return $this->numeroPlaqueImmatriculation;
    }

    public function setNumeroPlaqueImmatriculation(?string $numeroPlaqueImmatriculation): self
    {
        $this->numeroPlaqueImmatriculation = $numeroPlaqueImmatriculation;

        return $this;
    }

    public function isIsGroupeElectrogene(): ?bool
    {
        return $this->isGroupeElectrogene;
    }

    public function setIsGroupeElectrogene(bool $isGroupeElectrogene): self
    {
        $this->isGroupeElectrogene = $isGroupeElectrogene;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isIsCredit(): ?bool
    {
        return $this->isCredit;
    }

    public function setIsCredit(bool $isCredit): self
    {
        $this->isCredit = $isCredit;

        return $this;
    }

    public function getTotalMontant(): ?float
    {
        return $this->totalMontant;
    }

    public function setTotalMontant(float $totalMontant): self
    {
        $this->totalMontant = $totalMontant;

        return $this;
    }

    public function isIsServi(): ?bool
    {
        return $this->isServi;
    }

    public function setIsServi(bool $isServi): self
    {
        $this->isServi = $isServi;

        return $this;
    }

    public function getComptePetitClient(): ?ComptePetitClient
    {
        return $this->comptePetitClient;
    }

    public function setComptePetitClient(?ComptePetitClient $comptePetitClient): self
    {
        $this->comptePetitClient = $comptePetitClient;

        return $this;
    }

    public function getTypeCarburant(): ?string
    {
        return $this->typeCarburant;
    }

    public function setTypeCarburant(string $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }
}
