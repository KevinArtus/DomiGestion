<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     */
    private $codePostale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    public $latitude;
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    public $longitude;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $nbKm;

    /**
     * @var float
     *
     * @ORM\Column(type="time", nullable=true)
     */
    private $tempsRoute;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fixe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $portable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $anniversaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $preference;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    protected $pointCadeaux;

    /**
     * @ORM\Column(name="is_hote", type="boolean")
     */
    private $isHote;

    /**
     * Hôtesse chez qui le client a été rencontré pour la première fois
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Client")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hote;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reunion", mappedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reunionsParticipants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reunion", mappedBy="hote")
     */
    private $reunions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="client")
     */
    private $commandes;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->reunions = new ArrayCollection();
        $this->reunionsParticipants = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getFixe(): ?string
    {
        return $this->fixe;
    }

    public function setFixe(string $fixe): self
    {
        $this->fixe = $fixe;

        return $this;
    }

    public function getPortable(): ?string
    {
        return $this->portable;
    }

    public function setPortable(string $portable): self
    {
        $this->portable = $portable;

        return $this;
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

    public function getAnniversaire(): ?\DateTimeInterface
    {
        return $this->anniversaire;
    }

    public function setAnniversaire(\DateTimeInterface $anniversaire): self
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    /**
     * @return Collection|Reunion[]
     */
    public function getReunions(): Collection
    {
        return $this->reunions;
    }

    public function addReunion(Reunion $reunion): self
    {
        if (!$this->reunions->contains($reunion)) {
            $this->reunions[] = $reunion;
            $reunion->setHote($this);
        }

        return $this;
    }

    public function removeReunion(Reunion $reunion): self
    {
        if ($this->reunions->contains($reunion)) {
            $this->reunions->removeElement($reunion);
            // set the owning side to null (unless already changed)
            if ($reunion->getHote() === $this) {
                $reunion->setHote(null);
            }
        }

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
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getNbKm(): float
    {
        return $this->nbKm;
    }

    /**
     * @param float $nbKm
     */
    public function setNbKm(float $nbKm): void
    {
        $this->nbKm = $nbKm;
    }

    /**
     * @return float
     */
    public function getTempsRoute(): float
    {
        return $this->tempsRoute;
    }

    /**
     * @param float $tempsRoute
     */
    public function setTempsRoute(float $tempsRoute): void
    {
        $this->tempsRoute = $tempsRoute;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return mixed
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * @param mixed $preference
     */
    public function setPreference($preference): void
    {
        $this->preference = $preference;
    }

    /**
     * @return mixed
     */
    public function getPointCadeaux()
    {
        return $this->pointCadeaux;
    }

    /**
     * @param mixed $pointCadeaux
     */
    public function setPointCadeaux($pointCadeaux): void
    {
        $this->pointCadeaux = $pointCadeaux;
    }

    /**
     * @return mixed
     */
    public function getIsHote()
    {
        return $this->isHote;
    }

    /**
     * @param mixed $isHote
     */
    public function setIsHote($isHote): void
    {
        $this->isHote = $isHote;
    }

    /**
     * @return Client
     */
    public function getHote(): Client
    {
        return $this->hote;
    }

    /**
     * @param Client $hote
     */
    public function setHote(Client $hote): void
    {
        $this->hote = $hote;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|Reunion[]
     */
    public function getReunionsParticipants(): Collection
    {
        return $this->reunionsParticipants;
    }

    /**
     * @param mixed $reunions_participants
     */
    public function setReunionsParticipants($reunionsParticipants)
    {
        $this->reunionsParticipants = $reunionsParticipants;
    }

    public function __toString()
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }
}
