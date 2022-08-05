<?php

namespace App\Entity;

use App\Repository\SallesRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SallesRepository::class)]
class Salles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2, max:50)]
    #[Assert\NotBlank()]
    private ?string $designation = null;

    #[ORM\Column]
    #[Assert\Positive()]
    private ?int $numero = null;

    #[ORM\Column]
    #[Assert\Positive()]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    private ?string $caracteristique = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rmq = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Assert\LessThan(999999)]
    private ?float $frais = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getCaracteristique(): ?string
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(string $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getRmq(): ?string
    {
        return $this->rmq;
    }

    public function setRmq(?string $rmq): self
    {
        $this->rmq = $rmq;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): self
    {
        $this->frais = $frais;

        return $this;
    }
}
