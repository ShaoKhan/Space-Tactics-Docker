<?php

namespace App\Service;

use App\Repository\AllianceRepository;
use App\Repository\BuildingsRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlanetScienceRepository;
use App\Repository\SciencesRepository;

class UserService
{
    public function __construct(
        protected readonly BuildingsRepository $buildingsRepository,
        protected readonly PlanetRepository $planetRepository,
        protected readonly PlanetBuildingRepository $planetBuildingRepository,
        protected readonly SciencesRepository $sciencesRepository,
        protected readonly PlanetScienceRepository $planetScienceRepository,
        protected readonly AllianceRepository $allianceRepository
    ){}

    public function calculateBuildingPoints($user):int
    {
        $userPlanets = $this->planetRepository->findBy(['user_uuid' => $user->getUuid()]);
        $buildingPoints = 0;
        $points = 0;
        foreach($userPlanets as $planet){
            $planetBuildings = $this->planetBuildingRepository->findBy(['planet' => $planet]);

            foreach($planetBuildings as $building){

                $buildingData = $this->buildingsRepository->find($building->getId());
                $buildingLevel = $building->getBuildingLevel();
                $points = ($buildingData->getCostMetal() + $buildingData->getCostCrystal() + $buildingData->getCostDeuterium()) * $buildingData->getFactor() * (2 * (pow($buildingData->getFactor(), $buildingLevel)) - $buildingData->getFactor()) + 1;
            }
            $buildingPoints += $points;
        }
        return $buildingPoints;
    }

    public function calculateSciencePoints($user):int
    {
        $userPlanets = $this->planetRepository->findBy(['user_uuid' => $user->getUuid()]);
        $sciencePoints = 0;
        $points = 0;
        foreach($userPlanets as $planet){

            $planetScience = $this->planetScienceRepository->findBy(['planet' => $planet->getSlug()]);

            foreach($planetScience as $science){

                $scienceData = $this->sciencesRepository->find($science->getId());
                $scienceLevel = $science->getScienceLevel();

                $points += ($scienceData->getCostMetal() + $scienceData->getCostCrystal() + $scienceData->getCostDeuterium()) * $scienceData->getFactor() * (2 * (pow($scienceData->getFactor(), $scienceLevel)) - $scienceData->getFactor()) + 1;

            }
            $sciencePoints = $points;
        }
        return $sciencePoints;
    }

    public function calculateTotalPoints($user):int
    {
        $buildingPoints = $this->calculateBuildingPoints($user);
        $sciencePoints = $this->calculateSciencePoints($user);
        return $buildingPoints + $sciencePoints;
    }

    public function getAllianceByUuid($uuid)
    {
        return $this->allianceRepository->findOneBy(['slug' => $uuid]);
    }

}
