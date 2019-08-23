<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EntrepriseRepository")
 */
class Entreprise
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
    private $NomEntreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Linea;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RaisonSociale;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="entreprises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Systeme;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="Entreprise")
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="entreprise")
     */
    private $users;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->NomEntreprise;
    }

    public function setNomEntreprise(string $NomEntreprise): self
    {
        $this->NomEntreprise = $NomEntreprise;

        return $this;
    }

    public function getLinea(): ?string
    {
        return $this->Linea;
    }

    public function setLinea(string $Linea): self
    {
        $this->Linea = $Linea;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->RaisonSociale;
    }

    public function setRaisonSociale(string $RaisonSociale): self
    {
        $this->RaisonSociale = $RaisonSociale;

        return $this;
    }

    public function getSysteme(): ?User
    {
        return $this->Systeme;
    }

    public function setSysteme(?User $Systeme): self
    {
        $this->Systeme = $Systeme;

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
            $compte->setEntreprise($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getEntreprise() === $this) {
                $compte->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setEntreprise($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getEntreprise() === $this) {
                $user->setEntreprise(null);
            }
        }

        return $this;
    }
}
