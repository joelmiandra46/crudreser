<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SallesRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SallesRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity('designation')]
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
    private ?float $frais = null;

    #[ORM\OneToMany(mappedBy: 'idsalle', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Reservation::class)]
    private Collection $clt;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $color = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->clt = new ArrayCollection();
    }
    /**
     * Permet d'obtenir un tableau des jours qui ne sont pas disponibles
     *
     * @return array Un tableau d'objets datetime representant les dates d'occupation
     */
    public function getNotAvailableDays(){
        $notAvailableDays = [];
        foreach($this->clt as $reservation){
            //calculer les jours qui se trouve entre startdate et enddate
            $resultat = range(
                $reservation->getStartDate()->getTimestamp(),
                $reservation->getEndDate()->getTimestamp(),
                24 * 60 * 60
            );
            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d',$dayTimestamp));
            }, $resultat);

            $notAvailableDays = array_merge($notAvailableDays, $days);
        }
        return $notAvailableDays;
    }

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

    
    /**
     * @return Collection<int, Reservation>
     */
    public function getClt(): Collection
    {
        return $this->clt;
    }

    public function addClt(Reservation $clt): self
    {
        if (!$this->clt->contains($clt)) {
            $this->clt->add($clt);
            $clt->setSalle($this);
        }

        return $this;
    }

    public function removeClt(Reservation $clt): self
    {
        if ($this->clt->removeElement($clt)) {
            // set the owning side to null (unless already changed)
            if ($clt->getSalle() === $this) {
                $clt->setSalle(null);
            }
        }

        return $this;
    }
    public function  __toString(){
        return $this->designation;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
