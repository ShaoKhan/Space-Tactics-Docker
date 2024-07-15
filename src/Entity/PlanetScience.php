<?php

namespace App\Entity;

use App\Repository\PlanetScienceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetScienceRepository::class)]
class PlanetScience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Planet::class)]
    #[ORM\JoinColumn(name: 'planet_id', referencedColumnName: 'id', nullable: false)]
    private ?Planet $planet = null;

    #[ORM\ManyToOne(targetEntity: Sciences::class)]
    #[ORM\JoinColumn(name: 'science_id', referencedColumnName: 'id', nullable: false)]
    private ?Sciences $science = null;

    #[ORM\Column(length: 255)]
    private ?string $planetSlug = null;

    #[ORM\Column(length: 255)]
    private ?string $scienceSlug = null;

    #[ORM\Column]
    private ?int $scienceLevel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): self
    {
        $this->planet = $planet;
        return $this;
    }

    public function getScience(): ?Sciences
    {
        return $this->science;
    }

    public function setScience(?Sciences $science): self
    {
        $this->science = $science;
        return $this;
    }

    public function getPlanetSlug(): ?string
    {
        return $this->planetSlug;
    }

    public function setPlanetSlug(string $planetSlug): self
    {
        $this->planetSlug = $planetSlug;
        return $this;
    }

    public function getScienceSlug(): ?string
    {
        return $this->scienceSlug;
    }

    public function setScienceSlug(string $scienceSlug): self
    {
        $this->scienceSlug = $scienceSlug;
        return $this;
    }

    public function getScienceLevel(): ?int
    {
        return $this->scienceLevel;
    }

    public function setScienceLevel(int $scienceLevel): self
    {
        $this->scienceLevel = $scienceLevel;
        return $this;
    }
}
