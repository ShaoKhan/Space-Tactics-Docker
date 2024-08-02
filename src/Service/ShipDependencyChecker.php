<?php

namespace App\Service;

use App\Entity\BuildingDependency;
use App\Entity\Buildings;
use App\Entity\Planet;
use App\Entity\Sciences;
use App\Entity\Ships;
use App\Repository\PlanetBuildingRepository;
use App\Repository\ScienceDependenciesRepository;
use App\Repository\SciencesRepository;
use App\Repository\ShipDependenciesRepository;

readonly class ShipDependencyChecker
{

    public function __construct(
        protected ShipDependenciesRepository $shipDependenciesRepository,
        protected PlanetBuildingRepository     $planetBuildingRepository,
        protected SciencesRepository           $sciencesRepository,
    )
    {
    }

    public function checkResearchable(Ships $ship, Planet $planet): bool
    {
        /** @var BuildingDependency $shipDependencies */
        $shipDependencies = $this->shipDependenciesRepository->findBy(['ship' => $ship->getId()]);

        if($shipDependencies) {
            foreach($shipDependencies as $dependency) {
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
