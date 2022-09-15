<?php

namespace App\Entity;

use App\Repository\ActivationPostPayeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivationPostPayeRepository::class)]
#[ORM\Table(name:"ess_activation_post_paye")]
class ActivationPostPaye
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: ComptePetitClient::class, inversedBy: 'activationPostPayes')]
    #[ORM\JoinColumn(nullable: false)]
    private $comptePetitClient;

    #[ORM\Column(type: 'datetime')]
    private $dateDebut;

    #[ORM\Column(type: 'datetime')]
    private $dateFin;

    #[ORM\Column(type: 'dateinterval')]
    private $nombreJour;

    #[ORM\Column(type: 'boolean')]
    private $isCloture;

    #[ORM\Column(nullable:true)]
    private ?int $quantiteMaxDieselAutorise = null;

    #[ORM\Column(nullable:true)]
    private ?int $quantiteMaxEssenceAutorise = null;

    public function __toString()
    {
        return $this->comptePetitClient;
    }

    public function __construct(ComptePetitClient $comptePetitClient)
    {
        $this->comptePetitClient=$comptePetitClient;
        $this->dateDebut=new \DateTimeImmutable();
        $this->dateFin=new \DateTimeImmutable("+ 5 days");
        $this->quantiteMaxDieselAutorise=0;
        $this->quantiteMaxEssenceAutorise=0;
    }

    public function getId(): ?int
    {
        return $this->id;
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
