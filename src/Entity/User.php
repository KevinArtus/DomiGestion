<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * Class User
 * @package App\Entity
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(name="email", type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="login", type="string", length=25, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(name="roles", type="json", nullable = true )
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
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
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isAccountNonExpired()
    {
        return $this->isEnabled();
    }

    public function isAccountNonLocked()
    {
        return $this->isEnabled();
    }

    public function isCredentialsNonExpired()
    {
        return $this->isEnabled();
    }

    public function isEnabled()
    {
        return $this->active;
    }

    /**
     * Set isEnabled.
     *
     * @param $active
     *
     * @return User
     */
    public function setIsEnabled($active)
    {
        $this->active = (bool)$active;

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nom,
            $this->prenom,
            $this->password,
            $this->email,
            $this->login,
            $this->active,
            $this->roles
        ]);
    }

    /**
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->nom,
            $this->prenom,
            $this->password,
            $this->email,
            $this->login,
            $this->active,
            $this->roles
            ) = unserialize($serialized);
    }
}
