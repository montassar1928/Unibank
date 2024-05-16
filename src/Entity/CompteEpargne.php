<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteEpargneRepository;
use Symfony\Component\Validator\Constraints as Assert; // Import de la classe NotNull


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

    

    #[ORM\Column(name:"Prenom", type:"string", length:255, nullable:false)]
    #[Assert\NotNull(message:"Prenom should not be null")]
    private $prenom;



    #[ORM\Column(name:"Status", type:"boolean", nullable:false)]
    #[Assert\NotNull(message:"Status should not be null")]
    private $status;

    #[ORM\Column(name:"Image", type:"string", length:255, nullable:true)]
    private $image;

    #[ORM\Column(name:"Montant", type:"float", precision:10, scale:0, nullable:false)]
    #[Assert\NotNull(message:"Montant should not be null")]
    private $montant;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    private ?Users $iduser = null;    


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
    public function setCin(int $cin): self
    {
        $this->cin = $cin;
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
