<?php

namespace App\Entity;

use App\Repository\ApprovisionnementPetitClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprovisionnementPetitClientRepository::class)]
#[ORM\Table(name:"ess_approvisionnement_petit_client")]
#[ORM\HasLifecycleCallbacks()]
class ApprovisionnementPetitClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $utilisateur;

    #[ORM\ManyToOne(targetEntity: ComptePetitClient::class, inversedBy: 'approvisionnementPetitClients')]
    #[ORM\JoinColumn(nullable: true)]
    private $comptePetitClient;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'boolean')]
    private $isDiesel;

    #[ORM\Column(type: 'float')]
    private $montant;

    #[ORM\Column(type: 'string', length: 255)]
    private $bordereau;

    #[ORM\Column(type: 'string', length: 255)]
    private $numeroBordereau;

    #[ORM\Column(type: 'boolean')]
    private $isConfirme;

    #[ORM\Column]
    private ?int $quantiteEssence = null;

    #[ORM\Column]
    private ?int $quantiteDiesel = null;

    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        $comptePetitClient=$this->comptePetitClient;
        $compteGRCS=$comptePetitClient->getCompteGRCS();

        $quantiteDiesel=$this->quantiteDiesel;
        $quantiteEssence=$this->quantiteEssence;
        $this->comptePetitClient->setQuantiteDiesel($comptePetitClient->getQuantiteDiesel()+$quantiteDiesel);
        $this->comptePetitClient->setQuantiteEssence($comptePetitClient->getQuantiteEssence()+ $quantiteEssence);

        $compteGRCS->setQuantiteDiesel($compteGRCS->getQuantiteDiesel()-$quantiteDiesel);
        $compteGRCS->setQuantiteEssence($compteGRCS->getQuantiteEssence()-$quantiteEssence);

        ///calcul du montant 
        $grcs=$compteGRCS->getGrcs();
        $prixDiesel=$grcs->getPrixDiesel();
        $prixEssence=$grcs->getPrixEssence();
        $prixTotalDiesel=$quantiteDiesel * $prixDiesel;
        $prixTotalEssence=$quantiteEssence * $prixEssence;
        $this->montant= $prixTotalDiesel + $prixTotalEssence;
    }

    public function __construct(ComptePetitClient $comptePetitClient, Bool $isDiesel)
    {

        $this->comptePetitClient=$comptePetitClient;
        $this->isDiesel=$isDiesel;
        $this->date=new \DateTimeImmutable();
        $this->isConfirme=false;
        $this->quantiteDiesel=0;
        $this->quantiteEssence=0;
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

    public function getComptePetitClient(): ?ComptePetitClient
    {
        return $this->comptePetitClient;
    }

    public function setComptePetitClient(?ComptePetitClient $comptePetitClient): self
    {
        $this->comptePetitClient = $comptePetitClient;

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

    public function isIsDiesel(): ?bool
    {
        return $this->isDiesel;
    }

    public function setIsDiesel(bool $isDiesel): self
    {
        $this->isDiesel = $isDiesel;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getBordereau(): ?string
    {
        return $this->bordereau;
    }

    public function setBordereau(string $bordereau): self
    {
        $this->bordereau = $bordereau;

        return $this;
    }

    public function getNumeroBordereau(): ?string
    {
        return $this->numeroBordereau;
    }

    public function setNumeroBordereau(string $numeroBordereau): self
    {
        $this->numeroBordereau = $numeroBordereau;

        return $this;
    }

    public function isIsConfirme(): ?bool
    {
        return $this->isConfirme;
    }

    public function setIsConfirme(bool $isConfirme): self
    {
        $this->isConfirme = $isConfirme;

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

    public function getQuantiteDiesel(): ?int
    {
        return $this->quantiteDiesel;
    }

    public function setQuantiteDiesel(int $quantiteDiesel): self
    {
        $this->quantiteDiesel = $quantiteDiesel;

        return $this;
    }
}
