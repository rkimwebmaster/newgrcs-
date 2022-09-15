<?php

namespace App\Entity;

use App\Repository\MessagePetitClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessagePetitClientRepository::class)]
#[ORM\Table(name:"ess_message_petit_client")]
class MessagePetitClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\ManyToMany(targetEntity: PetitClient::class, inversedBy: 'messagePetitClients')]
    private $petitClient;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;

    public function __construct()
    {
        $this->petitClient = new ArrayCollection();
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

    /**
     * @return Collection<int, PetitClient>
     */
    public function getPetitClient(): Collection
    {
        return $this->petitClient;
    }

    public function addPetitClient(PetitClient $petitClient): self
    {
        if (!$this->petitClient->contains($petitClient)) {
            $this->petitClient[] = $petitClient;
        }

        return $this;
    }

    public function removePetitClient(PetitClient $petitClient): self
    {
        $this->petitClient->removeElement($petitClient);

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
