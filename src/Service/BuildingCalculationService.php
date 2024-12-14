<?php

namespace App\Service;

use App\Repository\BuildingsRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;

readonly class BuildingCalculationService
{

    public function __construct(
        protected PlanetBuildingRepository $planetBuildingRepository,
        protected BuildingsRepository      $buildingsRepository,
        protected PlanetRepository         $planetRepository,

    ) {}

    public function calculateNextBuildingLevelProduction($buildingId, $planetId): float
    {
        //get the built level of the building
        $buildingOnPlanet = $this->planetBuildingRepository->findOneBy(['planet' => $planetId, 'building' => $buildingId]);
        //get the building data
        $buildingData  = $this->buildingsRepository->findOneBy(['id' => $buildingId]);
        $buildingLevel = $buildingOnPlanet?->getBuildingLevel() ?? 1;

        return match ($buildingId) {
            1       => (30 * ($buildingLevel + 1) * pow((1.1), ($buildingLevel + 1))) * (0.1 * $buildingData->getFactor()),
            2, 4    => (20 * ($buildingLevel + 1) * pow((1.1), ($buildingLevel + 1))) * (0.1 * $buildingData->getFactor()),
            3       => (10 * ($buildingLevel + 1) * pow((1.1), ($buildingLevel + 1))) * (0.1 * $buildingData->getFactor()),
            default => 0.0,
        };
    }

    public function calculateNextBuildingLevelEnergyCosts(
        $buildingId,
        $planetId,
    ): float {
        //get the built level of the building
        $buildingOnPlanet = $this->planetBuildingRepository->findOneBy(['planet' => $planetId, 'building' => $buildingId]);
        //get the building data
        $buildingData = $this->buildingsRepository->findOneBy(['id' => $buildingId]);

        return match ($buildingId) {
            1, 2    => -(10 * ($buildingOnPlanet->getBuildingLevel() + 1) * pow((1.1), $buildingOnPlanet->getBuildingLevel())) * (0.1 * $buildingData->getFactor()),
            3       => -(30 * $buildingOnPlanet->getBuildingLevel() * pow((1.1), $buildingOnPlanet->getBuildingLevel())) * (0.1 * $buildingData->getFactor()),
            default => 0.0,
        };
    }

    public function calculateNextBuildingCosts(
        $buildingId,
        $planetId,
    ): array {
        //get the built level of the building
        $buildingOnPlanet = $this->planetBuildingRepository->findOneBy(['planet' => $planetId, 'building' => $buildingId]);
        //get the building data
        $buildingData  = $this->buildingsRepository->findOneBy(['id' => $buildingId]);
        $buildingLevel = $buildingOnPlanet?->getBuildingLevel() ?? 1;

        $buildCosts["metal"]       = ceil($buildingData->getCostMetal() * pow($buildingData->getFactor(), $buildingLevel + 1));
        $buildCosts["crystal"]     = ceil($buildingData->getCostCrystal() * pow($buildingData->getFactor(), $buildingLevel + 1));
        $buildCosts["deuterium"]   = ceil($buildingData->getCostDeuterium() * pow($buildingData->getFactor(), $buildingLevel + 1));
        $buildCosts["dark_matter"] = ceil($buildingData->getCostDarkMatter() * pow($buildingData->getFactor(), $buildingLevel + 1));
        return $buildCosts;

    }

    public function calculateActualBuildingProduction(
        $metalLevel,
        $crystalLevel,
        $deuteriumLevel,
    ): array {

        $metal     = $this->buildingsRepository->findOneBy(['id' => $metalLevel->getBuildingLevel()]);
        $crystal   = $this->buildingsRepository->findOneBy(['id' => $crystalLevel->getBuildingLevel()]);
        $deuterium = $this->buildingsRepository->findOneBy(['id' => $deuteriumLevel->getBuildingLevel()]);

        $metalPerHour     = (30 * $metalLevel->getBuildingLevel() * pow((1.1), $metalLevel->getBuildingLevel())) * (0.1 * $metal->getFactor());
        $crystalPerHour   = (20 * $crystalLevel->getBuildingLevel() * pow((1.1), $crystalLevel->getBuildingLevel())) * (0.1 * $crystal->getFactor());
        $deuteriumPerHour = (10 * $deuteriumLevel->getBuildingLevel() * pow((1.1), $deuteriumLevel->getBuildingLevel())) * (0.1 * $deuterium->getFactor());
        return [$metalPerHour, $crystalPerHour, $deuteriumPerHour];
    }
}
