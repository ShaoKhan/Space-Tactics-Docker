<?php

namespace App\Service;

use App\Entity\BuildingDependency;
use App\Entity\Buildings;
use App\Entity\Planet;
use App\Entity\Sciences;
use App\Repository\BuildingDependencyRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\ScienceDependenciesRepository;
use App\Repository\SciencesRepository;

readonly class ScienceDependencyChecker
{

    public function __construct(
        protected ScienceDependenciesRepository $scienceDependenciesRepository,
        protected PlanetBuildingRepository     $planetBuildingRepository,
        protected SciencesRepository           $sciencesRepository,
    )
    {
    }

    public function checkResearchable(Sciences $science, Planet $planet): bool
    {
        /** @var BuildingDependency $scienceDependencies */
        $scienceDependencies = $this->scienceDependenciesRepository->findBy(['science' => $science->getId()]);

        if($scienceDependencies) {
            foreach($scienceDependencies as $dependency) {
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
