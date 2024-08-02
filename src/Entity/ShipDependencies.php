<?php

namespace App\Entity;

use App\Repository\ShipDependenciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipDependenciesRepository::class)]
class ShipDependencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ships::class, inversedBy: "shipDependencies")]
    #[ORM\JoinColumn(name: "ship_id", referencedColumnName: "id", nullable: false)]
    private ?Ships $ship = null;

    #[ORM\ManyToOne(inversedBy: 'shipDependencies')]
    private ?Buildings $requiredBuilding = null;

    #[ORM\Column(nullable: true)]
    private ?int $requiredBuildingLevel = null;

    #[ORM\ManyToOne(inversedBy: 'shipDependencies')]
    private ?Sciences $requiredScience = null;

    #[ORM\Column(nullable: true)]
    private ?int $requiredScienceLevel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShip(): ?Ships
    {
        return $this->ship;
    }

    public function setShip(?Ships $ship): static
    {
        $this->ship = $ship;

        return $this;
    }

    public function getRequiredBuilding(): ?Buildings
    {
        return $this->requiredBuilding;
    }

    public function setRequiredBuilding(?Buildings $requiredBuilding): static
    {
        $this->requiredBuilding = $requiredBuilding;

        return $this;
    }

    public function getRequiredBuildingLevel(): ?int
    {
        return $this->requiredBuildingLevel;
    }

    public function setRequiredBuildingLevel(?int $requiredBuildingLevel): static
    {
        $this->requiredBuildingLevel = $requiredBuildingLevel;

        return $this;
    }

    public function getRequiredScience(): ?Sciences
    {
        return $this->requiredScience;
    }

    public function setRequiredScience(?Sciences $requiredScience): static
    {
        $this->requiredScience = $requiredScience;

        return $this;
    }

    public function getRequiredScienceLevel(): ?int
    {
        return $this->requiredScienceLevel;
    }

    public function setRequiredScienceLevel(?int $requiredScienceLevel): static
    {
        $this->requiredScienceLevel = $requiredScienceLevel;

        return $this;
    }
}
