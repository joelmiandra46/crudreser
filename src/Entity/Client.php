<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('tel')]
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    private ?string $nom = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    private ?string $tel = null;

    #[ORM\OneToMany(mappedBy: 'idclient', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Reservation::class)]
    private Collection $clt;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->clt = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

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
            $clt->setClient($this);
        }

        return $this;
    }

    public function removeClt(Reservation $clt): self
    {
        if ($this->clt->removeElement($clt)) {
            // set the owning side to null (unless already changed)
            if ($clt->getClient() === $this) {
                $clt->setClient(null);
            }
        }

        return $this;
    }

    public function  __toString(){
        return $this->nom;
    }
}
