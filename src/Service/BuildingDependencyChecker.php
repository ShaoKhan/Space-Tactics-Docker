<?php

namespace App\Service;

use App\Entity\PlanetBuilding;
use App\Entity\Sciences;
use App\Repository\BuildingDependencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class BuildingDependencyChecker
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface                          $entityManager,
        protected readonly BuildingDependencyRepository $buildingDependencyRepository,

    ) {
        $this->entityManager = $entityManager;
    }

    public function canConstructBuilding(int $buildingId, int $planetId): bool
    {
        $dependencies = $this->buildingDependencyRepository->findBy(['building' => $buildingId]);

        if($dependencies !== []) {
            foreach($dependencies as $dependency) {

                $requiredBuildingId    = $dependency->getRequiredBuilding()?->getId();
                $requiredBuildingLevel = $dependency->getRequiredBuildingLevel();
                $requiredScienceId     = $dependency->getRequiredScience()?->getId();
                $requiredScienceLevel  = $dependency->getRequiredScienceLevel();
                $buildingDependencyMet = FALSE;
                $scienceDependencyMet  = FALSE;

                if($requiredBuildingId) {
                    $requiredBuilding      = $this->findUserBuildingByPlanetIdAndBuildingId($requiredBuildingId, $planetId);
                    $buildingDependencyMet = $requiredBuilding && $requiredBuilding->getBuildingLevel() >= $requiredBuildingLevel;
                }

                if($requiredScienceId) {
                    $requiredScienceLevelByUser = $this->findUserScienceLevelById($requiredScienceId);
                    $scienceDependencyMet       = $requiredScienceLevel >= $requiredScienceLevelByUser;
                }

                if($buildingDependencyMet || $scienceDependencyMet) {
                    return TRUE; // At least one dependency is met
                }else{
                    return FALSE; // No dependencies are met
                }
            }
        }else{
            return TRUE; // No dependencies
        }

    }

    public function canBuildBuilding($building, $currentBuildings, $currentResearch): bool
    {
        dump($building);

        $dependencies = $this->buildingDependencyRepository->findBy(['building' => $building]);

        foreach ($dependencies as $dependency) {
            $requiredBuilding = $dependency->getRequiredBuilding();
            if ($requiredBuilding) {
                if (isset($currentBuildings[$requiredBuilding->getId()])) {
                    if ($currentBuildings[$requiredBuilding->getId()] < $dependency->getRequiredBuildingLevel()) {
                        return false;
                    }
                } else {
                    return false;
                }
            }

            $requiredResearch = $dependency->getRequiredScience();
            if ($requiredResearch) {
                if (isset($currentResearch[$requiredResearch->getId()])) {
                    if ($currentResearch[$requiredResearch->getId()] < $dependency->getRequiredScienceLevel()) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    private function findUserBuildingByPlanetIdAndBuildingId(
        int $buildingId,
        int $planetId,
    ) {
        $mr = $this->entityManager->getRepository(PlanetBuilding::class);
        return $mr->findOneBy(['building_id' => $buildingId, 'planet_id' => $planetId]);
    }

    private function findUserScienceLevelById(int $scienceId): int|null
    {
        if($scienceId === 1) {
            $mr = $this->entityManager->getRepository(Sciences::class);
            return $mr->findOneBy(['science' => $scienceId]);
        }

        return NULL;
    }
}
