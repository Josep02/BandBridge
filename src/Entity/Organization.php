<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, MusicianClass>
     */
    #[ORM\OneToMany(targetEntity: MusicianClass::class, mappedBy: 'organization')]
    private Collection $musician_class;

    #[ORM\ManyToOne(inversedBy: 'organization')]
    private ?OrganizationType $organization_type = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'organization')]
    private Collection $events;

    public function __construct()
    {
        $this->organization_type = null;
        $this->musician_class = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $musicianClass->setOrganization($this);
        }

        return $this;
    }

    public function removeMusicianClass(MusicianClass $musicianClass): static
    {
        if ($this->musician_class->removeElement($musicianClass)) {
            // set the owning side to null (unless already changed)
            if ($musicianClass->getOrganization() === $this) {
                $musicianClass->setOrganization(null);
            }
        }

        return $this;
    }

    public function getOrganizationType(): ?OrganizationType
    {
        return $this->organization_type;
    }

    public function setOrganizationType(?OrganizationType $organization_type): static
    {
        $this->organization_type = $organization_type;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setOrganization($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getOrganization() === $this) {
                $event->setOrganization(null);
            }
        }

        return $this;
    }
}
