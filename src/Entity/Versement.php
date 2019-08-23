<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\VersementRepository")
 */
class Versement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $Depot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="versements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="versements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Caissier;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepot(): ?string
    {
        return $this->Depot;
    }

    public function setDepot(string $Depot): self
    {
        $this->Depot = $Depot;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->Compte;
    }

    public function setCompte(?Compte $Compte): self
    {
        $this->Compte = $Compte;

        return $this;
    }

    public function getCaissier(): ?User
    {
        return $this->Caissier;
    }

    public function setCaissier(?User $Caissier): self
    {
        $this->Caissier = $Caissier;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }
}
