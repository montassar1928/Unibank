<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Form\ChoiceList\IdReader;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_r = null;


    #[Assert\NotBlank(message: 'Le montant souhaite est obligatoire')]
    #[ORM\Column(type: 'float')]
    private ?float $montant_r = null;

    #[Assert\NotBlank(message: 'La duree est obligatoire')]
    #[ORM\Column(type: 'integer')]
    private ?int $duree_r = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_r = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $statut_r = null;

    public function getId(): ?int
    {
        return $this->id_r;
    }

    public function getmontant_r(): ?float
    {
        return $this->montant_r;
    }

    public function ismontant_r(float $montant_r): self
    {
        $this->montant_r = $montant_r;

        return $this;
    }

    public function getduree_r(): ?int
    {
        return $this->duree_r;
    }

    public function isduree_r(int $duree_r): static
    {
        $this->duree_r = $duree_r;

        return $this;
    }

    public function getid_r(): ?int
    {
        return $this->id_r;
    }

    public function isid_r(int $id_r): static
    {
        $this->id_r = $id_r;

        return $this;
    }

    public function getdate_r(): ?\DateTimeInterface
    {
        return $this->date_r;
    }

    public function isdate_r(\DateTimeInterface $date_r): static
    {
        $this->date_r = $date_r;

        return $this;
    }

    public function getStatutR(): ?string
    {
        return $this->statut_r;
    }

    public function setStatutR(string $statut_r): static
    {
        $this->statut_r = $statut_r;

        return $this;
    }
}
