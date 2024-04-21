<?php

namespace App\Entity;

use App\Entity\Traits\CreateCode;
use App\Entity\Traits\Timestampable;
use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    use Timestampable, CreateCode;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $villeDept = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $villeArrv = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paysDept = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paysArrv = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDept = null;

    //#[ORM\Column(length: 255)]
    private ?int $heureDept = null;

    //#[ORM\Column(type: Types::SMALLINT)]
    private ?int $minuteDept = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $nbrDePlace = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2, nullable: true)]
    private ?string $prixPlace = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $rendezVsDept = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $rendezVsArrv = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $restrictions = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $marqVoiture = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $nbrePlaceArr = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $anneeNaiss = null;

    #[ORM\ManyToOne(inversedBy: 'trajets')]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'trajet')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleDept(): ?string
    {
        return $this->villeDept;
    }

    public function setVilleDept(?string $villeDept): static
    {
        $this->villeDept = $villeDept;

        return $this;
    }

    public function getVilleArrv(): ?string
    {
        return $this->villeArrv;
    }

    public function setVilleArrv(?string $villeArrv): static
    {
        $this->villeArrv = $villeArrv;

        return $this;
    }

    public function getPaysDept(): ?string
    {
        return $this->paysDept;
    }

    public function setPaysDept(?string $paysDept): static
    {
        $this->paysDept = $paysDept;

        return $this;
    }

    public function getPaysArrv(): ?string
    {
        return $this->paysArrv;
    }

    public function setPaysArrv(?string $paysArrv): static
    {
        $this->paysArrv = $paysArrv;

        return $this;
    }

    public function getDateDept(): ?\DateTimeImmutable
    {
        return $this->dateDept;
    }

    public function setDateDept(?\DateTimeImmutable $dateDept): static
    {
        $this->dateDept = $dateDept;

        return $this;
    }

    public function getHeureDept(): ?string
    {
        return $this->heureDept;
    }

    public function setHeureDept(?int $heureDept): static
    {
        $this->heureDept = $heureDept;

        return $this;
    }

    public function getMinuteDept(): ?int
    {
        return $this->minuteDept;
    }

    public function setMinuteDept(?int $minuteDept): static
    {
        $this->minuteDept = $minuteDept;

        return $this;
    }

    public function getNbrDePlace(): ?int
    {
        return $this->nbrDePlace;
    }

    public function setNbrDePlace(?int $nbrDePlace): static
    {
        $this->nbrDePlace = $nbrDePlace;

        return $this;
    }

    public function getPrixPlace(): ?string
    {
        return $this->prixPlace;
    }

    public function setPrixPlace(?string $prixPlace): static
    {
        $this->prixPlace = $prixPlace;

        return $this;
    }

    public function getRendezVsDept(): ?string
    {
        return $this->rendezVsDept;
    }

    public function setRendezVsDept(?string $rendezVsDept): static
    {
        $this->rendezVsDept = $rendezVsDept;

        return $this;
    }

    public function getRendezVsArrv(): ?string
    {
        return $this->rendezVsArrv;
    }

    public function setRendezVsArrv(?string $rendezVsArrv): static
    {
        $this->rendezVsArrv = $rendezVsArrv;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictions(?string $restrictions): static
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    public function getMarqVoiture(): ?string
    {
        return $this->marqVoiture;
    }

    public function setMarqVoiture(?string $marqVoiture): static
    {
        $this->marqVoiture = $marqVoiture;

        return $this;
    }

    public function getNbrePlaceArr(): ?int
    {
        return $this->nbrePlaceArr;
    }

    public function setNbrePlaceArr(?int $nbrePlaceArr): static
    {
        $this->nbrePlaceArr = $nbrePlaceArr;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAnneeNaiss(): ?\DateTimeInterface
    {
        return $this->anneeNaiss;
    }

    public function setAnneeNaiss(?\DateTimeInterface $anneeNaiss): static
    {
        $this->anneeNaiss = $anneeNaiss;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTrajet($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTrajet() === $this) {
                $reservation->setTrajet(null);
            }
        }

        return $this;
    }
}
