<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(
    fields: ['client', 'salle'],
    errorPath: 'salle',
    message: 'Cette salle a déjà été réserver par cet client.',
)]
//#[UniqueEntity(
//    fields: ['salle'],
//    errorPath: 'salle',
//    message: 'Cette salle a déjà été réserver, choisissez un autre.',
//)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan('today',message:"La date d'arrivée doit etre ultérieur a la date d'aujourd'hui !")]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan(propertyPath:'startDate',message:"La date de départ doit etre plus éloignée que la date d'arrivée !")]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
   // #[Assert\Positive(message:"entrer un montant positive")]
   // #[Assert\NotBlank()]
    private ?float $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $statutReservation = null;

    #[ORM\ManyToOne(inversedBy: 'clt')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'clt')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salles $salle = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;


    #[ORM\PrePersist]
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt =  new \DateTime();
        }
        if(empty($this->montant)) {
            //prix du salle  * nombre de jour
            $this->montant = $this->salle->getFrais() * $this->getDuration();
        }
    }

    public function isBookableDates(){
        //1-il faut connaitre les dates qui sont impossible
        $notAvailableDays =  $this->salle->getNotAvailableDays();
        //2-comparer les dates choisies avec les dates imposibble
        $bookingDays = $this->getDays();

        $formatDay = function($day){
            return $day->format('Y-m-d');
        };
        //tableau des chaines de caracteres
        $days = array_map($formatDay,  $bookingDays);
        $notAvailable = array_map($formatDay,  $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) return false;
        }

        return true;

    }
    /**
     * Permet de recuperer un tableau des journees qui correspondent a la reservation
     * 
     * @return array tableau d'objet datetime representant les jours de reservations
     */
    public function getDays() {
            $resultat = range(
                $this->startDate->getTimestamp(),
                $this->endDate->getTimestamp(),
                24 * 60 * 60
            );
            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('Y-m-d', $dayTimestamp));
            }, $resultat);
            return $days;
    }

    public function getDuration(){
        $diff =  $this->endDate->diff($this->startDate);
        return $diff->days;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getStatutReservation(): ?string
    {
        return $this->statutReservation;
    }

    public function setStatutReservation(string $statutReservation): self
    {
        $this->statutReservation = $statutReservation;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSalle(): ?Salles
    {
        return $this->salle;
    }

    public function setSalle(?Salles $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

}
