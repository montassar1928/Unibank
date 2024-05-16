<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "numOperation", type: "integer")]
    private ?int $numOperation = null;

    #[ORM\Column(name: "typeOperation", type: "string", length: 255)]
    private string $typeOperation;

    #[ORM\Column(name: "dateOperation", type: "datetime")]
    private \DateTimeInterface $dateOperation;

    #[ORM\Column(name: "description", type: "string", length: 50)]
    private string $description;

    #[ORM\Column(name: "statusOperation", type: "boolean")]
    private bool $statusOperation;

    #[ORM\Column(name: "montantOpt", type: "float")]
    private float $montantOpt;

    #[ORM\ManyToOne(targetEntity: VirementInternational::class)]
    #[ORM\JoinColumn(name: "ref", referencedColumnName: "ref")]
    private $ref;
    private $send;
    private $from;

    public function getSend(): ?string
    {
        return $this->send;
    }

    public function setSend(int $send): self
    {
        $this->send = $send;

        return $this;
    }
   


    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }
    public function getNumOperation(): ?int
    {
        return $this->numOperation;
    }
    public function __construct()
    {
        $this->dateOperation = new \DateTime();
    }
    public function getTypeOperation(): string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(string $typeOperation): void
    {
        $this->typeOperation = $typeOperation;
    }

    public function getDateOperation(): \DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): void
    {
        $this->dateOperation = $dateOperation;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getStatusOperation(): bool
    {
        return $this->statusOperation;
    }

    public function setStatusOperation(bool $statusOperation): void
    {
        $this->statusOperation = $statusOperation;
    }

    public function getMontantOpt(): float
    {
        return $this->montantOpt;
    }

    public function setMontantOpt(float $montantOpt): void
    {
        $this->montantOpt = $montantOpt;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }
}
