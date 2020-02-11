<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParkingRepository")
 * @ApiResource(
 *     attributes={
 *      "order"={
 *          "nom":"ASC"
 *      }
 *     },
 *     itemOperations={
 *       "get_parking_simple"={
 *          "method"="GET",
 *          "path"="/parkings/{id}/simple",
 *          "normalization_context"={"groups"={"parking:simple"}}
 *      },
 *       "get_parking_complete"={
 *          "method"="GET",
 *          "path"="/parkings/{id}/complete",
 *          "normalization_context"={"groups"={"parking:complete"}}
 *      }
 *     },
 *     collectionOperations={
 *       "get_parking_list_simple"={
 *          "method"="GET",
 *          "path"="/parkings/simple",
 *          "normalization_context"={"groups"={"parking:simple"}}
 *      },
 *       "get_parking_list_complete"={
 *          "method"="GET",
 *          "path"="/parkings/complete",
 *          "normalization_context"={"groups"={"parking:complete"}}
 *      }
 *     }
 * )
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "nom":"ipartial"
 *     }
 * )
 */
class Parking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"parking:simple", "parking:complete"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"parking:simple", "parking:complete"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     */
    private $latidude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="parking", orphanRemoval=true)
     * @ApiSubresource()
     * @Groups("parking:complete")
     */
    private $bookings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getLatidude(): ?string
    {
        return $this->latidude;
    }

    public function setLatidude(string $latidude): self
    {
        $this->latidude = $latidude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setParking($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getParking() === $this) {
                $booking->setParking(null);
            }
        }

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom();
    }
}
