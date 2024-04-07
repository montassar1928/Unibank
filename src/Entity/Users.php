<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'This email is already registered.')]
class Users implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: 'string', length: 40, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $Raison_Sociale = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $telephone = null;

    #[ORM\Column(name: 'dateDeNaissance', type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateDeNaissance = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?string $banned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->Raison_Sociale;
    }

    public function setRaisonSociale(?string $Raison_Sociale): self
    {
        $this->Raison_Sociale = $Raison_Sociale;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->dateDeNaissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $dateDeNaissance): self
    {
        $this->dateDeNaissance = $dateDeNaissance;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles(): array
    {
        // Vous pouvez convertir la valeur de l'attribut enum en un tableau de rôles
        // selon la logique de votre application
        return ['ROLE_' . strtoupper($this->role)];
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->email;
    }

    public function getBanned(): ?string
    {
        return $this->banned;
    }

    public function setBanned(?string $banned): self
    {
        $this->banned = $banned;

        return $this;
    }
}
