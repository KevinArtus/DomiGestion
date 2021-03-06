<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReunionRepository")
 */
class Reunion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montantHt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montantTTC;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cycle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kmEffectue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fraisDeplacement;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $benefice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reunions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="reunions")
     */
    private $commandes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="reunions")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", inversedBy="participerReunion")
     */
    private $participants;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->commandes = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function getMontantHt(): ?float
    {
        return $this->montantHt;
    }

    public function setMontantHt(?float $montantHt): self
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getMontantTTC(): ?float
    {
        return $this->montantTTC;
    }

    public function setMontantTTC(?float $montantTTC): self
    {
        $this->montantTTC = $montantTTC;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * @param mixed $cycle
     */
    public function setCycle($cycle): void
    {
        $this->cycle = $cycle;
    }

    /**
     * @return mixed
     */
    public function getKmEffectue()
    {
        return $this->kmEffectue;
    }

    /**
     * @param mixed $kmEffectue
     */
    public function setKmEffectue($kmEffectue): void
    {
        $this->kmEffectue = $kmEffectue;
    }

    /**
     * @return mixed
     */
    public function getFraisDeplacement()
    {
        return $this->fraisDeplacement;
    }

    /**
     * @param mixed $fraisDeplacement
     */
    public function setFraisDeplacement($fraisDeplacement): void
    {
        $this->fraisDeplacement = $fraisDeplacement;
    }

    /**
     * @return mixed
     */
    public function getBenefice()
    {
        return $this->benefice;
    }

    /**
     * @param mixed $benefice
     */
    public function setBenefice($benefice): void
    {
        $this->benefice = $benefice;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setReunions($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getReunions() === $this) {
                $commande->setReunions(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getDate()->format('d-m-Y H:i').' - '.$this->getClient()->__toString();
    }

    /**
     * @return Collection|Client[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Client $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Client $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }

        return $this;
    }
}
