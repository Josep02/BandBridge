<?php

namespace App\Entity;

use App\Repository\DetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, ParticipationRequest>
     */
    #[ORM\OneToMany(targetEntity: ParticipationRequest::class, mappedBy: 'detail')]
    private Collection $participationRequests;

    public function __construct()
    {
        $this->participationRequests = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, ParticipationRequest>
     */
    public function getParticipationRequests(): Collection
    {
        return $this->participationRequests;
    }

    public function addParticipationRequest(ParticipationRequest $participationRequest): static
    {
        if (!$this->participationRequests->contains($participationRequest)) {
            $this->participationRequests->add($participationRequest);
            $participationRequest->setDetail($this);
        }

        return $this;
    }

    public function removeParticipationRequest(ParticipationRequest $participationRequest): static
    {
        if ($this->participationRequests->removeElement($participationRequest)) {
            // set the owning side to null (unless already changed)
            if ($participationRequest->getDetail() === $this) {
                $participationRequest->setDetail(null);
            }
        }

        return $this;
    }
}
