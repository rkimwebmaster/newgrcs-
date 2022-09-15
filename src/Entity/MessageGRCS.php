<?php

namespace App\Entity;

use App\Repository\MessageGRCSRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MessageGRCSRepository::class)]
#[ORM\Table(name: "ess_message_grcs")]
class MessageGRCS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\ManyToOne(targetEntity: GRCS::class, inversedBy: 'messageGRCS')]
    #[ORM\JoinColumn(nullable: false)]
    private $grcs;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messageGRCS')]
    private $utilisateur;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;

    public function __construct(GRCS $grcs, UserInterface $user)
    {
        $this->grcs = $grcs;
        $this->utilisateur = $user;
        $this->date = new \DateTimeImmutable();
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

    public function getGrcs(): ?GRCS
    {
        return $this->grcs;
    }

    public function setGrcs(?GRCS $grcs): self
    {
        $this->grcs = $grcs;

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
