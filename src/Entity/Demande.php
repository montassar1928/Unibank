<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[Assert\NotBlank(message: 'Le montant souhaité est obligatoire')]
    #[Assert\GreaterThan(value:1000, message:"Le montant doit être supérieur à 1000")]
    #[ORM\Column(type: 'float')]
    private ?float $montant = null;

    #[Assert\NotBlank(message: 'Le revenu annuel est obligatoire')]
    #[Assert\GreaterThan(value:1000, message:"Le revenu doit être supérieur à 1000")]
    #[ORM\Column(type: 'float')]
    private ?float $revenu = null;

    #[Assert\NotBlank(message: 'La durée est obligatoire')]
    #[Assert\GreaterThan(value:12, message:"La duree doit être supérieur à 12")]
    #[ORM\Column(type: 'integer')]
    private ?int $duree = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $statut = null;
    #[ORM\ManyToOne(targetEntity: Users::class)]

    private ?Users $iduser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getRevenu(): ?float
    {
        return $this->revenu;
    }

    public function setRevenu(float $revenu): self
    {
        $this->revenu = $revenu;
        return $this;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getIduser(): ?users
    {
        return $this->iduser;
    }

    public function setIduser(?users $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }
}

