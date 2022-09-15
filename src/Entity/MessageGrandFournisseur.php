<?php

namespace App\Entity;

use App\Repository\MessageGrandFournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageGrandFournisseurRepository::class)]
#[ORM\Table(name:"ess_message_grand_fournisseur")]
class MessageGrandFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'text')]
    private $contenu;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messageGrandFournisseurs')]
    private $utilisateur;

    #[ORM\ManyToMany(targetEntity: GrandFournisseur::class, inversedBy: 'messageGrandFournisseurs')]
    private $grandFournisseurs;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;

    public function __construct()
    {
        $this->grandFournisseurs = new ArrayCollection();
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    /**
     * @return Collection<int, GrandFournisseur>
     */
    public function getGrandFournisseurs(): Collection
    {
        return $this->grandFournisseurs;
    }

    public function addGrandFournisseur(GrandFournisseur $grandFournisseur): self
    {
        if (!$this->grandFournisseurs->contains($grandFournisseur)) {
            $this->grandFournisseurs[] = $grandFournisseur;
        }

        return $this;
    }

    public function removeGrandFournisseur(GrandFournisseur $grandFournisseur): self
    {
        $this->grandFournisseurs->removeElement($grandFournisseur);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }
}
