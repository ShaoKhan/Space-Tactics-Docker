<?php

// src/Entity/BuildingDependency.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "building_dependencies")]
class BuildingDependency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Buildings::class)]
    #[ORM\JoinColumn(name: "building_id", referencedColumnName: "id", nullable: false)]
    private $building;

    #[ORM\ManyToOne(targetEntity: Buildings::class)]
    #[ORM\JoinColumn(name: "required_building_id", referencedColumnName: "id", nullable: true)]
    private $requiredBuilding = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $requiredBuildingLevel = null;

    #[ORM\ManyToOne(targetEntity: Sciences::class)]
    #[ORM\JoinColumn(name: "required_science_id", referencedColumnName: "id", nullable: true)]
    private $requiredScience = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private $requiredScienceLevel = null;

    // Getters and setters for the above properties

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
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param mixed $building
     */
    public function setBuilding($building): void
    {
        $this->building = $building;
    }

    /**
     * @return mixed
     */
    public function getRequiredBuilding()
    {
        return $this->requiredBuilding;
    }

    /**
     * @param mixed $requiredBuilding
     */
    public function setRequiredBuilding($requiredBuilding): void
    {
        $this->requiredBuilding = $requiredBuilding;
    }

    /**
     * @return int|null
     */
    public function getRequiredBuildingLevel(): ?int
    {
        return $this->requiredBuildingLevel;
    }

    /**
     * @param mixed $requiredBuildingLevel
     */
    public function setRequiredBuildingLevel(?int $requiredBuildingLevel): void
    {
        $this->requiredBuildingLevel = $requiredBuildingLevel;
    }

    public function getRequiredScience():null
    {
        return $this->requiredScience;
    }

    /**
     * @param mixed $requiredScience
     */
    public function setRequiredScience($requiredScience): void
    {
        $this->requiredScience = $requiredScience;
    }

    /**
     * @return mixed
     */
    public function getRequiredScienceLevel()
    {
        return $this->requiredScienceLevel;
    }

    /**
     * @param mixed $requiredScienceLevel
     */
    public function setRequiredScienceLevel($requiredScienceLevel): void
    {
        $this->requiredScienceLevel = $requiredScienceLevel;
    }

}

