<?php

namespace App\Entity;

use App\Repository\ApprovisionnementGRCSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprovisionnementGRCSRepository::class)]
#[ORM\Table(name:"ess_approvisionnement_grcs")]
#[ORM\HasLifecycleCallbacks()]
class ApprovisionnementGRCS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: CompteGRCS::class, inversedBy: 'approvisionnementGRCS')]
    private $compteGRCS;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private $utilisateur;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'boolean')]
    private $isConfirme;

    #[ORM\Column(type: 'boolean')]
    private $isDiesel;

    #[ORM\Column]
    private ?int $quantiteEssence = null;

    #[ORM\Column]
    private ?int $quantiteDiesel = null;


    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        $quantiteDiesel=$this->quantiteDiesel;
        $quantiteEssence=$this->quantiteEssence;
        $this->compteGRCS->setQuantiteDiesel($this->compteGRCS->getQuantiteDiesel()+$quantiteDiesel);
        $this->compteGRCS->setQuantiteEssence($this->compteGRCS->getQuantiteEssence()+$quantiteEssence);
    }

    public function __construct(CompteGRCS $compteGRCS)
    {
        $this->compteGRCS=$compteGRCS;
        $this->date=new \DateTimeImmutable();
        $this->isConfirme=false;
        $this->isDiesel=false;
        $this->quantiteDiesel=0;
        $this->quantiteEssence=0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isIsConfirme(): ?bool
    {
        return $this->isConfirme;
    }

    public function setIsConfirme(bool $isConfirme): self
    {
        $this->isConfirme = $isConfirme;

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
