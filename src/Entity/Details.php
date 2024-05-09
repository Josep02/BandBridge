<?php

namespace App\Entity;

use App\Repository\DetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsRepository::class)]
class Details
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $min_payment = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    private ?Event $Event = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    private ?Instrument $requiredInstrument = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinPayment(): ?float
    {
        return $this->min_payment;
    }

    public function setMinPayment(float $min_payment): static
    {
        $this->min_payment = $min_payment;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->Event;
    }

    public function setEvent(?Event $Event): static
    {
        $this->Event = $Event;

        return $this;
    }

    public function getRequiredInstrument(): ?Instrument
    {
        return $this->requiredInstrument;
    }

    public function setRequiredInstrument(?Instrument $requiredInstrument): static
    {
        $this->requiredInstrument = $requiredInstrument;

        return $this;
    }
}
