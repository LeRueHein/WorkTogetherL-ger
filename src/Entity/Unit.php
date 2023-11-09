<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $locationSlot = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rack $rack = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeUnit $typeUnit = null;

    #[ORM\ManyToOne(inversedBy: 'units')]
    private ?Reservation $reservation = null;

    #[ORM\Column]
    private ?bool $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocationSlot(): ?int
    {
        return $this->locationSlot;
    }

    public function setLocationSlot(int $locationSlot): static
    {
        $this->locationSlot = $locationSlot;

        return $this;
    }

    public function getRack(): ?Rack
    {
        return $this->rack;
    }

    public function setRack(?Rack $rack): static
    {
        $this->rack = $rack;

        return $this;
    }

    public function getTypeUnit(): ?TypeUnit
    {
        return $this->typeUnit;
    }

    public function setTypeUnit(?TypeUnit $typeUnit): static
    {
        $this->typeUnit = $typeUnit;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;

        return $this;
    }
}
