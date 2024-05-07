<?php

namespace App\Entity;

use App\Repository\MusicianRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicianRepository::class)]
class Musician
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, MusicianClass>
     */
    #[ORM\OneToMany(targetEntity: MusicianClass::class, mappedBy: 'musician')]
    private Collection $musician_class;

    /**
     * @var Collection<int, Valoration>
     */
    #[ORM\OneToMany(targetEntity: Valoration::class, mappedBy: 'musician')]
    private Collection $valorations;

    #[ORM\ManyToOne(inversedBy: 'musicians')]
    private ?Instrument $Instrument = null;

    public function __construct()
    {
        $this->musician_class = new ArrayCollection();
        $this->valorations = new ArrayCollection();
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, MusicianClass>
     */
    public function getMusicianClass(): Collection
    {
        return $this->musician_class;
    }

    public function addMusicianClass(MusicianClass $musicianClass): static
    {
        if (!$this->musician_class->contains($musicianClass)) {
            $this->musician_class->add($musicianClass);
            $musicianClass->setMusician($this);
        }

        return $this;
    }

    public function removeMusicianClass(MusicianClass $musicianClass): static
    {
        if ($this->musician_class->removeElement($musicianClass)) {
            // set the owning side to null (unless already changed)
            if ($musicianClass->getMusician() === $this) {
                $musicianClass->setMusician(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Valoration>
     */
    public function getValorations(): Collection
    {
        return $this->valorations;
    }

    public function addValoration(Valoration $valoration): static
    {
        if (!$this->valorations->contains($valoration)) {
            $this->valorations->add($valoration);
            $valoration->setMusician($this);
        }

        return $this;
    }

    public function removeValoration(Valoration $valoration): static
    {
        if ($this->valorations->removeElement($valoration)) {
            // set the owning side to null (unless already changed)
            if ($valoration->getMusician() === $this) {
                $valoration->setMusician(null);
            }
        }

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->Instrument;
    }

    public function setInstrument(?Instrument $Instrument): static
    {
        $this->Instrument = $Instrument;

        return $this;
    }
}
