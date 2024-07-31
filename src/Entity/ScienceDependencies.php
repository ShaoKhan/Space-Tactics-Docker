<?php

namespace App\Entity;

use App\Repository\ScienceDependenciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScienceDependenciesRepository::class)]
class ScienceDependencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Sciences::class, inversedBy: "scienceDependencies")]
    #[ORM\JoinColumn(name: "science_id", referencedColumnName: "id", nullable: false)]
    private $science;

    #[ORM\ManyToOne(targetEntity: Sciences::class)]
    #[ORM\JoinColumn(name: "required_science_id", referencedColumnName: "id", nullable: true)]
    private $requiredScience = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $requiredScienceLevel = null;

    #[ORM\ManyToOne(targetEntity: Buildings::class, inversedBy: "scienceDependencies")]
    #[ORM\JoinColumn(name: "required_building_id", referencedColumnName: "id", nullable: true)]
    private $requiredBuilding = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private $requiredBuildingLevel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getScience(): Sciences
    {
        return $this->science;
    }


    public function setScience(?Sciences $science): self
    {
        $this->science = $science;
        return $this;
    }

    public function getRequiredScience(): ?Sciences
    {
        return $this->requiredScience;
    }


    public function setRequiredScience(?Sciences $requiredScience): self
    {
        $this->requiredScience = $requiredScience;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRequiredScienceLevel(): ?int
    {
        return $this->requiredScienceLevel;
    }

    public function setRequiredScienceLevel(?int $requiredScienceLevel): self
    {
        $this->requiredScienceLevel = $requiredScienceLevel;
        return $this;
    }

    public function getRequiredBuilding(): ?Buildings
    {
        return $this->requiredBuilding;
    }

    public function setRequiredBuilding(?Buildings $requiredBuilding): self
    {
        $this->requiredBuilding = $requiredBuilding;
        return $this;
    }

    public function getRequiredBuildingLevel(): ?int
    {
        return $this->requiredBuildingLevel;
    }

    public function setRequiredBuildingLevel(?int $requiredBuildingLevel): self
    {
        $this->requiredBuildingLevel = $requiredBuildingLevel;
        return $this;
    }

}
