<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ApiResource()
 * @ApiFilter(DateFilter::class, properties={"dateFin"})
 * @UniqueEntity(fields={"parking", "numero"})
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("parking:complete")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("parking:complete")
     * @Assert\Date
     * @var string A "Y-m-d H:m:n" formatted value
     * @Assert\LessThan(propertyPath="dateFin")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("parking:complete")
     * @Assert\Date
     * @var string A "Y-m-d H:m:n" formatted value
     * @Assert\GreaterThan(propertyPath="dateDebut")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("parking:complete")
     * @Assert\Email
     */
    private $utilisateurEmail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Parking", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Assert\NotBlank()
     */
    private $parking;

    /**
     * @ORM\Column(type="integer")
     * @Groups("parking:complete")
     * @Assert\NotBlank()
     */
    private $numero;

    public function __construct()
    {
        $this->dateDebut =  new \DateTime();
        $this->dateFin =  new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getUtilisateurEmail(): ?string
    {
        return $this->utilisateurEmail;
    }

    public function setUtilisateurEmail(string $utilisateurEmail): self
    {
        $this->utilisateurEmail = $utilisateurEmail;

        return $this;
    }

    public function getParking(): ?Parking
    {
        return $this->parking;
    }

    public function setParking(?Parking $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getParking()->getNom() . ": " . $this->getNumero();
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
}