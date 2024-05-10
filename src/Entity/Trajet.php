<?php

namespace App\Entity;

use App\Entity\Traits\CreateCode;
use App\Entity\Traits\Timestampable;
use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Trajet
{
    use Timestampable, CreateCode;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Entrer votre ville de départ et le pays départ!')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom de la ville doit avoir au moins {{ limit }} lettres.',
        maxMessage: 'Le nom de la ville doit avoir au plus {{ limit }} lettres.'
    )]
    private ?string $villeDept = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Entrer la ville d\'arrivée et le pays d\'arrivée!')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom de la ville doit avoir au moins {{ limit }} lettres.',
        maxMessage: 'Le nom de la ville doit avoir au plus {{ limit }} lettres.'
    )]
    private ?string $villeArrv = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paysDept = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paysArrv = null;

    #[ORM\Column(nullable: true)]
    // #[Assert\NotBlank(message: 'Entrer votre date de départ (ex:20/01/2020)')]
    private ?\DateTimeImmutable $dateDept = null;

    //#[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Entrer l\'heure de départ.')]
    #[Assert\PositiveOrZero(message: 'Donner un chiffre positif.')]
    private ?int $heureDept = null;

    //#[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'Préciser les minutes.')]
    #[Assert\PositiveOrZero(message: 'Donner un chiffre positif.')]
    private ?int $minuteDept = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Assert\NotBlank(message: 'Entrer le nombre de place disponble.')]
    #[Assert\Positive(message: 'Donner un nombre positif différent de zero.')]
    private ?int $nbrDePlace = null;

    // #[ORM\Column(type: Types::DECIMAL, precision: 9, scale: 2, nullable: true)]
    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Entrer le prix que coûte une place.')]
    #[Assert\PositiveOrZero(message: 'Entrer un nombre positif ou zéro si les places sont gratuites.')]
    // private ?string $prixPlace = null;
    private ?int $prixPlace = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Entrer le lieu du rendez-vous avec les passagers au départ.')]
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

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Votre adresse email est indispensable pour recevoir les réservations des passagers')]
    #[Assert\Email(
        message: '{{ value }} n\'est pas une adresse email valide.',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\NotBlank(message: 'Votre numéro de téléphone est indispensable pour les passagers')]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $anneeNaiss = null;

    #[ORM\ManyToOne(inversedBy: 'trajets')]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'trajet')]
    private Collection $reservations;

    private ?string $pattern = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->pattern = "`.*([^1-9])*[1-9]([^1-9])*(([0-9]([^1-9])*){2}){4}`";
        $this->publish = true;
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

    // public function getPrixPlace(): ?string
    // {
    //     return $this->prixPlace;
    // }

    // public function setPrixPlace(?string $prixPlace): static
    // {
    //     $this->prixPlace = $prixPlace;

    //     return $this;
    // }

    public function getPrixPlace(): ?int
    {
        return $this->prixPlace;
    }

    public function setPrixPlace(?int $prixPlace): static
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

    #[Assert\Callback]
    public function isDescriptionValid(ExecutionContextInterface $context): void
    {
        // $pattern = "`.*([^1-9])*[1-9]([^1-9])*(([0-9]([^1-9])*){2}){4}`";
        // On vérifie que le contenu de ne contient pas de Num phone
        if (preg_match($this->pattern, $this->getDescription())) {
            // La règle est violée, on définit l'erreur
            $context
                ->buildViolation('Contenu invalide car il contient un numéro de téléphone') //message
                ->atPath('description')                                                   //attribut de l'objet qui est violé
                ->addViolation();  //ceci déclenche l'erreur
        }
    }

    #[Assert\Callback]
    public function isRestrictionsValid(ExecutionContextInterface $context): void
    {
        if (preg_match($this->pattern, $this->getRestrictions())) {
            $context
                ->buildViolation('Contenu invalide car il contient un numéro de téléphone')
                ->atPath('restrictions')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function isPhoneValid(ExecutionContextInterface $context): void
    {
        if (preg_match($this->pattern, $this->getPhone())) {
            $context
                ->buildViolation('Numéro de téléphone invalide (Ex: 07532214)')
                ->atPath('phone')
                ->addViolation();
        }
    }
}
