<?php

namespace App\Entity;

use App\Repository\VirementInternationalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VirementInternationalRepository::class)]
#[ORM\Table(name: "virement_international")]
class VirementInternational
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private ?int $ref = null;

    #[ORM\Column(type: "float")]
    private ?float $tauxEchange;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name;

    public function getRef(): ?int
    {
        return $this->ref;
    }

    public function setRef(int $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getTauxEchange(): ?float
    {
        return $this->tauxEchange;
    }

    public function setTauxEchange(float $tauxEchange): self
    {
        $this->tauxEchange = $tauxEchange;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        // Assuming you have a property named 'name' in your VirementInternational entity
        return $this->name ?? '';
    }
}
