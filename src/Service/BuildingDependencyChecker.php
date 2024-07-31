<?php

namespace App\Service;

use App\Entity\BuildingDependency;
use App\Entity\Buildings;
use App\Entity\Planet;
use App\Repository\BuildingDependencyRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\SciencesRepository;

readonly class BuildingDependencyChecker
{

    public function __construct(
        protected BuildingDependencyRepository $buildingDependencyRepository,
        protected PlanetBuildingRepository     $planetBuildingRepository,
        protected SciencesRepository           $sciencesRepository,

    )
    {
    }

    public function checkBuildable(Buildings $building, $planet): bool
    {
        /** @var BuildingDependency $buildingDependencies */
        $buildingDependencies = $this->buildingDependencyRepository->findBy(['building' => $building->getId()]);

        if($buildingDependencies) {
            foreach($buildingDependencies as $dependency) {
                $requiredBuildingType  = $dependency->getRequiredBuilding();
                $requiredBuildingLevel = $dependency->getRequiredBuildingLevel();
                $requiredScienceId     = $dependency->getRequiredScience()?->getId();
                $requiredScienceLevel  = $dependency->getRequiredScienceLevel();
                $buildingDependencyMet = FALSE;
                $scienceDependencyMet  = FALSE;

                if($requiredBuildingType) {
                    $requiredBuilding      = $this->findUserBuildingByPlanetIdAndBuildingId($requiredBuildingType, $planet);
                    $buildingDependencyMet = $requiredBuilding && $requiredBuilding->getBuildingLevel() >= $requiredBuildingLevel;
                }

                if($requiredScienceId) {
                    $requiredScienceLevelByUser = $this->findUserScienceLevelById($requiredScienceId);
                    $scienceDependencyMet       = $requiredScienceLevel >= $requiredScienceLevelByUser;
                }

                if($buildingDependencyMet || $scienceDependencyMet) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    private function findUserBuildingByPlanetIdAndBuildingId(
        Buildings $building,
        Planet    $planet,
    ) {
        return $this->planetBuildingRepository->findOneBy(['building' => $building, 'planet' => $planet]);
    }

    private function findUserScienceLevelById(int $scienceId): int|null
    {
        if($scienceId === 1) {
            return $this->sciencesRepository->findOneBy(['science' => $scienceId]);
        }
        return NULL;
    }
}
