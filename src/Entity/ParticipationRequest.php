<?php

namespace App\Entity;

use App\Repository\ParticipationRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRequestRepository::class)]
class ParticipationRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'participationRequests')]
    private ?Musician $musician = null;

    #[ORM\ManyToOne(inversedBy: 'participationRequests')]
    private ?Event $event = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $applicationDate = null;

    #[ORM\ManyToOne(inversedBy: 'participationRequests')]
    private ?Details $detail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getMusician(): ?Musician
    {
        return $this->musician;
    }

    public function setMusician(?Musician $musician): static
    {
        $this->musician = $musician;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getApplicationDate(): ?\DateTimeInterface
    {
        return $this->applicationDate;
    }

    public function setApplicationDate(\DateTimeInterface $applicationDate): static
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    public function getDetail(): ?Details
    {
        return $this->detail;
    }

    public function setDetail(?Details $detail): static
    {
        $this->detail = $detail;

        return $this;
    }
}
