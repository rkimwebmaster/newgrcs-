<?php

namespace App\Entity;

use App\Repository\CompteGRCSRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteGRCSRepository::class)]
#[ORM\Table(name:"ess_compte_grcs")]
#[ORM\HasLifecycleCallbacks()]
class CompteGRCS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\OneToOne(inversedBy: 'compteGRCS', targetEntity: GrandFournisseur::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $grandFournisseur;

    #[ORM\Column(type: 'integer')]
    private $quantiteDiesel;

    #[ORM\Column(type: 'integer')]
    private $quantiteEssence;

    #[ORM\Column(type: 'datetime')]
    private $dateDernierApprovisionnement;

    #[ORM\OneToMany(mappedBy: 'compteGRCS', targetEntity: ComptePetitClient::class)]
    private $comptePetitClients;

    #[ORM\OneToMany(mappedBy: 'compteGRCS', targetEntity: ApprovisionnementGRCS::class)]
    private $approvisionnementGRCS;

    #[ORM\ManyToOne(targetEntity: GRCS::class, inversedBy: 'compteGRCS')]
    #[ORM\JoinColumn(nullable: false)]
    private $grcs;

    #[ORM\OneToMany(mappedBy: 'compteGRCS', targetEntity: ActivationPostPayeGRCS::class)]
    private Collection $activationPostPayeGRCS;

    #[ORM\Column(nullable: true)]
    private ?bool $isPostPaye = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentageDiesel = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentageEssence = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteDieselNonServie = null;

    #[ORM\Column(nullable: true)]
    private ?int $qteEssenceNonServie = null;

    
    #[ORM\PrePersist]
    #[ORM\PostLoad]
    public function misAJour(){
        $qteStockRecommandeDiesel=$this->getGrcs()->getQteStockRecommandeDiesel();
        $qteStockRecommandeEssence=$this->getGrcs()->getQteStockRecommandeEssence();
        $qteDiesel=$this->quantiteDiesel;
        $qteEssence=$this->quantiteEssence;

        if($qteStockRecommandeDiesel>0 and $qteStockRecommandeEssence >0){
            $pourcentageDiesel=$qteDiesel / $qteStockRecommandeDiesel;
            $pourcentageEssence=$qteEssence / $qteStockRecommandeEssence;
        }else{
        $pourcentageDiesel=1;
        $pourcentageEssence=1;
        }
        $this->pourcentageDiesel=$pourcentageDiesel;
        $this->pourcentageEssence=$pourcentageEssence;
    }

    public function __toString()
    {
        return $this->grandFournisseur.' - '.$this->grcs ;
    }

    public function __construct(GrandFournisseur $grandFournisseur)
    {
        $this->grandFournisseur=$grandFournisseur;
        $this->comptePetitClients = new ArrayCollection();
        $this->approvisionnementGRCS = new ArrayCollection();
        $this->quantiteDiesel=0;
        $this->quantiteEssence=0;
        $this->qteDieselNonServie=0;
        $this->qteEssenceNonServie=0;
        $this->dateDernierApprovisionnement=new \DateTimeImmutable();
        $this->activationPostPayeGRCS = new ArrayCollection();
        $this->isPostPaye=false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrandFournisseur(): ?GrandFournisseur
    {
        return $this->grandFournisseur;
    }

    public function setGrandFournisseur(GrandFournisseur $grandFournisseur): self
    {
        $this->grandFournisseur = $grandFournisseur;

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

    public function getQuantiteEssence(): ?int
    {
        return $this->quantiteEssence;
    }

    public function setQuantiteEssence(int $quantiteEssence): self
    {
        $this->quantiteEssence = $quantiteEssence;

        return $this;
    }

    public function getDateDernierApprovisionnement(): ?\DateTimeInterface
    {
        return $this->dateDernierApprovisionnement;
    }

    public function setDateDernierApprovisionnement(\DateTimeInterface $dateDernierApprovisionnement): self
    {
        $this->dateDernierApprovisionnement = $dateDernierApprovisionnement;

        return $this;
    }

    /**
     * @return Collection<int, ComptePetitClient>
     */
    public function getComptePetitClients(): Collection
    {
        return $this->comptePetitClients;
    }

    public function addComptePetitClient(ComptePetitClient $comptePetitClient): self
    {
        if (!$this->comptePetitClients->contains($comptePetitClient)) {
            $this->comptePetitClients[] = $comptePetitClient;
            $comptePetitClient->setCompteGRCS($this);
        }

        return $this;
    }

    public function removeComptePetitClient(ComptePetitClient $comptePetitClient): self
    {
        if ($this->comptePetitClients->removeElement($comptePetitClient)) {
            // set the owning side to null (unless already changed)
            if ($comptePetitClient->getCompteGRCS() === $this) {
                $comptePetitClient->setCompteGRCS(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApprovisionnementGRCS>
     */
    public function getApprovisionnementGRCS(): Collection
    {
        return $this->approvisionnementGRCS;
    }

    public function addApprovisionnementGRC(ApprovisionnementGRCS $approvisionnementGRC): self
    {
        if (!$this->approvisionnementGRCS->contains($approvisionnementGRC)) {
            $this->approvisionnementGRCS[] = $approvisionnementGRC;
            $approvisionnementGRC->setCompteGRCS($this);
        }

        return $this;
    }

    public function removeApprovisionnementGRC(ApprovisionnementGRCS $approvisionnementGRC): self
    {
        if ($this->approvisionnementGRCS->removeElement($approvisionnementGRC)) {
            // set the owning side to null (unless already changed)
            if ($approvisionnementGRC->getCompteGRCS() === $this) {
                $approvisionnementGRC->setCompteGRCS(null);
            }
        }

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

    /**
     * @return Collection<int, ActivationPostPayeGRCS>
     */
    public function getActivationPostPayeGRCS(): Collection
    {
        return $this->activationPostPayeGRCS;
    }

    public function addActivationPostPayeGRC(ActivationPostPayeGRCS $activationPostPayeGRC): self
    {
        if (!$this->activationPostPayeGRCS->contains($activationPostPayeGRC)) {
            $this->activationPostPayeGRCS->add($activationPostPayeGRC);
            $activationPostPayeGRC->setCompteGRCS($this);
        }

        return $this;
    }

    public function removeActivationPostPayeGRC(ActivationPostPayeGRCS $activationPostPayeGRC): self
    {
        if ($this->activationPostPayeGRCS->removeElement($activationPostPayeGRC)) {
            // set the owning side to null (unless already changed)
            if ($activationPostPayeGRC->getCompteGRCS() === $this) {
                $activationPostPayeGRC->setCompteGRCS(null);
            }
        }

        return $this;
    }

    public function isIsPostPaye(): ?bool
    {
        return $this->isPostPaye;
    }

    public function setIsPostPaye(?bool $isPostPaye): self
    {
        $this->isPostPaye = $isPostPaye;

        return $this;
    }

    public function getPourcentageDiesel(): ?float
    {
        return $this->pourcentageDiesel;
    }

    public function setPourcentageDiesel(?float $pourcentageDiesel): self
    {
        $this->pourcentageDiesel = $pourcentageDiesel;

        return $this;
    }

    public function getPourcentageEssence(): ?float
    {
        return $this->pourcentageEssence;
    }

    public function setPourcentageEssence(?float $pourcentageEssence): self
    {
        $this->pourcentageEssence = $pourcentageEssence;

        return $this;
    }

    public function getQteDieselNonServie(): ?int
    {
        return $this->qteDieselNonServie;
    }

    public function setQteDieselNonServie(?int $qteDieselNonServie): self
    {
        $this->qteDieselNonServie = $qteDieselNonServie;

        return $this;
    }

    public function getQteEssenceNonServie(): ?int
    {
        return $this->qteEssenceNonServie;
    }

    public function setQteEssenceNonServie(?int $qteEssenceNonServi): self
    {
        $this->qteEssenceNonServie = $qteEssenceNonServi;

        return $this;
    }
}
