<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NomComplet;

    /**
     * @ORM\Column(type="bigint")
     */
    private $NCI;

    /**
     * @ORM\Column(type="bigint")
     */
    private $Tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entreprise", mappedBy="Systeme")
     */
    private $entreprises;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="Systeme")
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Versement", mappedBy="Caissier")
     */
    private $versements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="users")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entreprise", inversedBy="users")
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="CreePar")
     */
    private $CreePart;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="CreePart")
     */
    private $CreePar;

    public function __construct()
    {
        $this->entreprises = new ArrayCollection();
        $this->comptes = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->CreePar = new ArrayCollection();
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
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomComplet(): ?string
    {
        return $this->NomComplet;
    }

    public function setNomComplet(string $NomComplet): self
    {
        $this->NomComplet = $NomComplet;

        return $this;
    }

    public function getNCI(): ?string
    {
        return $this->NCI;
    }

    public function setNCI(string $NCI): self
    {
        $this->NCI = $NCI;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->IsActive;
    }

    public function setIsActive(?bool $IsActive): self
    {
        $this->IsActive = $IsActive;

        return $this;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->setSysteme($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->contains($entreprise)) {
            $this->entreprises->removeElement($entreprise);
            // set the owning side to null (unless already changed)
            if ($entreprise->getSysteme() === $this) {
                $entreprise->setSysteme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setSysteme($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getSysteme() === $this) {
                $compte->setSysteme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Versement[]
     */
    public function getVersements(): Collection
    {
        return $this->versements;
    }

    public function addVersement(Versement $versement): self
    {
        if (!$this->versements->contains($versement)) {
            $this->versements[] = $versement;
            $versement->setCaissier($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): self
    {
        if ($this->versements->contains($versement)) {
            $this->versements->removeElement($versement);
            // set the owning side to null (unless already changed)
            if ($versement->getCaissier() === $this) {
                $versement->setCaissier(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getCreePart(): ?self
    {
        return $this->CreePart;
    }

    public function setCreePart(?self $CreePart): self
    {
        $this->CreePart = $CreePart;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCreePar(): Collection
    {
        return $this->CreePar;
    }

    public function addCreePar(self $creePar): self
    {
        if (!$this->CreePar->contains($creePar)) {
            $this->CreePar[] = $creePar;
            $creePar->setCreePart($this);
        }

        return $this;
    }

    public function removeCreePar(self $creePar): self
    {
        if ($this->CreePar->contains($creePar)) {
            $this->CreePar->removeElement($creePar);
            // set the owning side to null (unless already changed)
            if ($creePar->getCreePart() === $this) {
                $creePar->setCreePart(null);
            }
        }

        return $this;
    }
}
