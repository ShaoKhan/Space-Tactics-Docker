<?php

namespace App\Entity;

use App\Repository\PlanetBuildingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetBuildingRepository::class)]
class PlanetBuilding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = NULL;

    #[ORM\ManyToOne(targetEntity: Planet::class, inversedBy: 'planetBuildings')]
    #[ORM\JoinColumn(name: 'planet_id', referencedColumnName: 'id', nullable: FALSE)]
    private ?Planet $planet = NULL;

    #[ORM\ManyToOne(targetEntity: Buildings::class)]
    #[ORM\JoinColumn(name: 'building_id', referencedColumnName: 'id', nullable: FALSE)]
    private ?Buildings $building = NULL;

    #[ORM\Column]
    private ?int $building_level = NULL;

    #[ORM\Column(length: 255)]
    private ?string $planet_slug = NULL;

    #[ORM\Column(length: 255)]
    private ?string $building_slug = NULL;

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

    public function getBuilding(): ?Buildings
    {
        return $this->building;
    }

    public function setBuilding(?Buildings $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getBuildingLevel(): ?int
    {
        return $this->building_level;
    }

    public function setBuildingLevel(int $building_level): self
    {
        $this->building_level = $building_level;

        return $this;
    }

    public function getPlanetSlug(): ?string
    {
        return $this->planet_slug;
    }

    public function setPlanetSlug(string $planet_slug): self
    {
        $this->planet_slug = $planet_slug;

        return $this;
    }

    public function getBuildingSlug(): ?string
    {
        return $this->building_slug;
    }

    public function setBuildingSlug(string $building_slug): self
    {
        $this->building_slug = $building_slug;

        return $this;
    }
}
