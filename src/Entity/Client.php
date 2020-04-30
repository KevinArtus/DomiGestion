<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    const CIV_MONSIEUR = 'M';
    const CIV_MADAME = 'Mme';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3, nullable=false)
     * @Assert\Length(min= 1, max = 3)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(min="2", max="5")
     */
    private $codePostale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @Assert\Length(max=10)
     */
    private $fixe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=10)
     */
    private $portable;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Email
     * @Assert\Length(max = 200)
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true)
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
     * @ORM\Column(name="is_hote", type="boolean", options={"default":0})
     */
    private $isHote;

    /**
     * Le client a-t-il été importé depuis un fichier excel.
     *
     * @ORM\Column(name="importer", type="boolean")
     */
    private $importer;

    /**
     * Hôtesse chez qui le client a été rencontré pour la première fois.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Client")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hote;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="client")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reunion", mappedBy="client")
     */
    private $reunions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reunion", mappedBy="participants")
     */
    private $participerReunion;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->commandes = new ArrayCollection();
        $this->importer = false;
        $this->reunions = new ArrayCollection();
        $this->participerReunion = new ArrayCollection();
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

    public function setVille(string $ville = null): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getFixe(): ?string
    {
        return $this->fixe;
    }

    public function setFixe(string $fixe = null): self
    {
        $this->fixe = $fixe;

        return $this;
    }

    public function getPortable(): ?string
    {
        return $this->portable;
    }

    public function setPortable(string $portable = null): self
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

    public function setAnniversaire(\DateTimeInterface $anniversaire = null)
    {
        $this->anniversaire = $anniversaire;
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
            $reunion->setClient($this);
        }

        return $this;
    }

    public function removeReunion(Reunion $reunion): self
    {
        if ($this->reunions->contains($reunion)) {
            $this->reunions->removeElement($reunion);
            // set the owning side to null (unless already changed)
            if ($reunion->getClient() === $this) {
                $reunion->setClient(null);
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

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getNbKm(): float
    {
        return $this->nbKm;
    }

    public function setNbKm(float $nbKm): void
    {
        $this->nbKm = $nbKm;
    }

    public function getTempsRoute(): float
    {
        return $this->tempsRoute;
    }

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

    public function getHote(): Client
    {
        return $this->hote;
    }

    public function setHote(Client $hote): void
    {
        $this->hote = $hote;
    }

    /**
     * @return mixed
     */
    public function getImporter()
    {
        return $this->importer;
    }

    /**
     * @param mixed $importer
     */
    public function setImporter($importer): void
    {
        $this->importer = $importer;
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

    public function __toString()
    {
        return $this->getPrenom().' '.$this->getNom();
    }

    public function formatedAddress()
    {
        return $this->getAdresse().' '.$this->getCodePostale().' '.$this->getVille();
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * @return Collection|Reunion[]
     */
    public function getParticiperReunion(): Collection
    {
        return $this->participerReunion;
    }

    public function addParticiperReunion(Reunion $participerReunion): self
    {
        if (!$this->participerReunion->contains($participerReunion)) {
            $this->participerReunion[] = $participerReunion;
            $participerReunion->addParticipant($this);
        }

        return $this;
    }

    public function removeParticiperReunion(Reunion $participerReunion): self
    {
        if ($this->participerReunion->contains($participerReunion)) {
            $this->participerReunion->removeElement($participerReunion);
            $participerReunion->removeParticipant($this);
        }

        return $this;
    }
}
