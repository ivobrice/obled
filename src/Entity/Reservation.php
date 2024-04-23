<?php

namespace App\Entity;

use App\Entity\Traits\CreateCode;
use App\Entity\Traits\Timestampable;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    use Timestampable, CreateCode;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trajet $trajet = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phonePassager = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $nbrDePlaceRsrv = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2, nullable: true)]
    private ?string $prixPlaceRsrv = null;

   #[ORM\Column(length: 100, nullable: true)]
    private ?string $villeDept = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $villeArrv = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDept = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $mailPassager = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $mailChauf = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phoneChauf = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateValidationClient = null;

    #[ORM\Column(nullable: true)]
    private ?bool $paiement = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTrajet(): ?Trajet
    {
        return $this->trajet;
    }

    public function setTrajet(?Trajet $trajet): static
    {
        $this->trajet = $trajet;

        return $this;
    }

    public function getPhonePassager(): ?string
    {
        return $this->phonePassager;
    }

    public function setPhonePassager(?string $phonePassager): static
    {
        $this->phonePassager = $phonePassager;

        return $this;
    }

    public function getNbrDePlaceRsrv(): ?int
    {
        return $this->nbrDePlaceRsrv;
    }

    public function setNbrDePlaceRsrv(?int $nbrDePlaceRsrv): static
    {
        $this->nbrDePlaceRsrv = $nbrDePlaceRsrv;

        return $this;
    }

    public function getPrixPlaceRsrv(): ?string
    {
        return $this->prixPlaceRsrv;
    }

    public function setPrixPlaceRsrv(?string $prixPlaceRsrv): static
    {
        $this->prixPlaceRsrv = $prixPlaceRsrv;

        return $this;
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

    public function getDateDept(): ?\DateTimeInterface
    {
        return $this->dateDept;
    }

    public function setDateDept(?\DateTimeInterface $dateDept): static
    {
        $this->dateDept = $dateDept;

        return $this;
    }

    public function getMailPassager(): ?string
    {
        return $this->mailPassager;
    }

    public function setMailPassager(?string $mailPassager): static
    {
        $this->mailPassager = $mailPassager;

        return $this;
    }

    public function getMailChauf(): ?string
    {
        return $this->mailChauf;
    }

    public function setMailChauf(?string $mailChauf): static
    {
        $this->mailChauf = $mailChauf;

        return $this;
    }

    public function getPhoneChauf(): ?string
    {
        return $this->phoneChauf;
    }

    public function setPhoneChauf(?string $phoneChauf): static
    {
        $this->phoneChauf = $phoneChauf;

        return $this;
    }

    public function getDateValidationClient(): ?\DateTimeImmutable
    {
        return $this->dateValidationClient;
    }

    public function setDateValidationClient(?\DateTimeImmutable $dateValidationClient): static
    {
        $this->dateValidationClient = $dateValidationClient;

        return $this;
    }

    public function isPaiement(): ?bool
    {
        return $this->paiement;
    }

    public function setPaiement(?bool $paiement): static
    {
        $this->paiement = $paiement;

        return $this;
    }
}
