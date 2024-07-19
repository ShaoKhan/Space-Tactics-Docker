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

    #[ORM\ManyToOne(targetEntity: Sciences::class)]
    #[ORM\JoinColumn(name: "science_id", referencedColumnName: "id", nullable: false)]
    private $science;

    #[ORM\ManyToOne(targetEntity: Sciences::class)]
    #[ORM\JoinColumn(name: "required_science_id", referencedColumnName: "id", nullable: true)]
    private $requiredScience = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $requiredScienceLevel = null;

    #[ORM\ManyToOne(targetEntity: Buildings::class)]
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
    public function getScience()
    {
        return $this->science;
    }

    /**
     * @param mixed $science
     */
    public function setScience($science): void
    {
        $this->science = $science;
    }

    /**
     * @return mixed
     */
    public function getRequiredScience(): null
    {
        return $this->requiredScience;
    }

    /**
     * @param mixed $requiredScience
     */
    public function setRequiredScience(null $requiredScience): void
    {
        $this->requiredScience = $requiredScience;
    }

    /**
     * @return int|null
     */
    public function getRequiredScienceLevel(): ?int
    {
        return $this->requiredScienceLevel;
    }

    public function setRequiredScienceLevel(?int $requiredScienceLevel): void
    {
        $this->requiredScienceLevel = $requiredScienceLevel;
    }

    public function getRequiredBuilding(): null
    {
        return $this->requiredBuilding;
    }

    public function setRequiredBuilding($requiredBuilding): void
    {
        $this->requiredBuilding = $requiredBuilding;
    }

    public function getRequiredBuildingLevel(): null
    {
        return $this->requiredBuildingLevel;
    }

    public function setRequiredBuildingLevel($requiredBuildingLevel): void
    {
        $this->requiredBuildingLevel = $requiredBuildingLevel;
    }

}
