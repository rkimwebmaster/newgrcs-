<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name:"ess_message")]
#[ORM\HasLifecycleCallbacks()]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    private ?User $expediteurUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $expediteurStructure = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?GRCS $destinataireGRCS = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?GrandFournisseur $destinataireFournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?PetitClient $destinataireClient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $sujet = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isLu = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function misAJour(){
        ///mettre a jour le compteur des messages et notofications 
        if($grcs=$this->destinataireGRCS){
            $grcs->incrementerMessage();
            $this->expediteurStructure=$grcs;
            // dd($grcs->getNombreMessage());
        }elseif($fournisseur=$this->destinataireFournisseur){
            $fournisseur->incrementerMessage();
            $this->expediteurStructure=$fournisseur->getEntreprise();

        }elseif($client=$this->destinataireClient){
            $client->incrementerMessage();
            $this->expediteurStructure=$client;
        }
        //mise a jour du nom de la structure 
        // $this->expediteurStructure="Système";
    }

    public function __construct()
    {
       $this->date= new \DateTimeImmutable();
       $this->isLu=false;
       $this->createdAt=new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getExpediteurUser(): ?User
    {
        return $this->expediteurUser;
    }

    public function setExpediteurUser(?User $expediteurUser): self
    {
        $this->expediteurUser = $expediteurUser;

        return $this;
    }

    public function getExpediteurStructure(): ?string
    {
        return $this->expediteurStructure;
    }

    public function setExpediteurStructure(?string $expediteurStructure): self
    {
        $this->expediteurStructure = $expediteurStructure;

        return $this;
    }

    public function getDestinataireGRCS(): ?GRCS
    {
        return $this->destinataireGRCS;
    }

    public function setDestinataireGRCS(?GRCS $destinataireGRCS): self
    {
        $this->destinataireGRCS = $destinataireGRCS;

        return $this;
    }

    public function getDestinataireFournisseur(): ?GrandFournisseur
    {
        return $this->destinataireFournisseur;
    }

    public function setDestinataireFournisseur(?GrandFournisseur $destinataireFournisseur): self
    {
        $this->destinataireFournisseur = $destinataireFournisseur;

        return $this;
    }

    public function getDestinataireClient(): ?PetitClient
    {
        return $this->destinataireClient;
    }

    public function setDestinataireClient(?PetitClient $destinataireClient): self
    {
        $this->destinataireClient = $destinataireClient;

        return $this;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function isIsLu(): ?bool
    {
        return $this->isLu;
    }

    public function setIsLu(?bool $isLu): self
    {
        $this->isLu = $isLu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
