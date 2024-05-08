<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'instruments')]
    private ?Classification $classification = null;

    /**
     * @var Collection<int, Musician>
     */
    #[ORM\OneToMany(targetEntity: Musician::class, mappedBy: 'Instrument')]
    private Collection $musicians;

    public function __construct()
    {
        $this->musicians = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): static
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * @return Collection<int, Musician>
     */
    public function getMusicians(): Collection
    {
        return $this->musicians;
    }

    public function addMusician(Musician $musician): static
    {
        if (!$this->musicians->contains($musician)) {
            $this->musicians->add($musician);
            $musician->setInstrument($this);
        }

        return $this;
    }

    public function removeMusician(Musician $musician): static
    {
        if ($this->musicians->removeElement($musician)) {
            // set the owning side to null (unless already changed)
            if ($musician->getInstrument() === $this) {
                $musician->setInstrument(null);
            }
        }

        return $this;
    }
}
