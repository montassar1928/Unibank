<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteEpargneRepository;

#[ORM\Entity(repositoryClass: CompteEpargneRepository::class)]
class CompteEpargne
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"ID", type:"integer", nullable:false)]
    private $id;

    #[ORM\Column(name:"cin", type:"integer", nullable:false)]
    #[Assert\Length( exactMessage:"CIN should be exactly 8 digits",min:8,max:8)]
    #[Assert\NotNull(message:"cin should not be null")]
    private $cin;

    #[ORM\Column(name:"Nom", type:"string", length:255, nullable:false)]
    #[Assert\NotNull(message:"Nom should not be null")]
    private $nom;

    #[ORM\Column(name:"Prenom", type:"string", length:255, nullable:false)]
    #[Assert\NotNull(message:"Prenom should not be null")]
    private $prenom;

    #[ORM\Column(name:"Age", type:"integer", nullable:false)]
    #[Assert\NotNull(message:"Age should not be null")]
    private $age;

    #[ORM\Column(name:"Telephone", type:"integer", nullable:false)]
    #[Assert\NotNull(message:"Telephone should not be null")]
    #[Assert\Length( exactMessage:"Telephone should be exactly 8 digits",min:8,max:8)]
    private $telephone;

    #[ORM\Column(name:"Status", type:"boolean", nullable:false)]
    #[Assert\NotNull(message:"Status should not be null")]
    private $status;

    #[ORM\Column(name:"Image", type:"string", length:255, nullable:false)]
    private $image;

    #[ORM\Column(name:"Montant", type:"float", precision:10, scale:0, nullable:false)]
    #[Assert\NotNull(message:"Montant should not be null")]
    private $montant;


    public function getcin(): ?int
    {
        return $this->cin;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }


}
