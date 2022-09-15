<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`ess_user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?GRCS $grcs = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?GrandFournisseur $grandFournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?PetitClient $petitClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;


    public function __toString()
    {
        $nom=explode('@',$this->email);
        return $nom[0];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getGrandFournisseur(): ?GrandFournisseur
    {
        return $this->grandFournisseur;
    }

    public function setGrandFournisseur(?GrandFournisseur $grandFournisseur): self
    {
        $this->grandFournisseur = $grandFournisseur;

        return $this;
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

}
