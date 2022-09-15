<?php

namespace App\Entity;

use App\Repository\ActivationPostPayeGRCSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivationPostPayeGRCSRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class ActivationPostPayeGRCS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activationPostPayeGRCS')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CompteGRCS $compteGRCS = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?\DateInterval $nombreJour = null;

    #[ORM\Column]
    private ?bool $isCloture = null;

    #[ORM\Column]
    private ?int $quantiteMaxDieselAutorise = null;

    #[ORM\Column]
    private ?int $quantiteMaxEssenceAutorise = null;

    
    #[ORM\PrePersist]
    public function misAJour(){
     $compteGRCS=$this->compteGRCS;
     $compteGRCS->setIsPostPaye(true);
    }

    public function __toString()
    {
        return $this->compteGRCS;
    }


    public function __construct(CompteGRCS $compteGRCS)
    {
        $this->compteGRCS=$compteGRCS;
        $this->dateDebut=new \DateTimeImmutable();
        $this->dateFin=new \DateTimeImmutable("+ 5 days");
        $this->quantiteMaxDieselAutorise=0;
        $this->quantiteMaxEssenceAutorise=0;
        $this->isCloture=false;
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNombreJour(): ?\DateInterval
    {
        return $this->nombreJour;
    }

    public function setNombreJour(\DateInterval $nombreJour): self
    {
        $this->nombreJour = $nombreJour;

        return $this;
    }

    public function isIsCloture(): ?bool
    {
        return $this->isCloture;
    }

    public function setIsCloture(bool $isCloture): self
    {
        $this->isCloture = $isCloture;

        return $this;
    }

    public function getQuantiteMaxDieselAutorise(): ?int
    {
        return $this->quantiteMaxDieselAutorise;
    }

    public function setQuantiteMaxDieselAutorise(int $quantiteMaxDieselAutorise): self
    {
        $this->quantiteMaxDieselAutorise = $quantiteMaxDieselAutorise;

        return $this;
    }

    public function getQuantiteMaxEssenceAutorise(): ?int
    {
        return $this->quantiteMaxEssenceAutorise;
    }

    public function setQuantiteMaxEssenceAutorise(int $quantiteMaxEssenceAutorise): self
    {
        $this->quantiteMaxEssenceAutorise = $quantiteMaxEssenceAutorise;

        return $this;
    }
}
