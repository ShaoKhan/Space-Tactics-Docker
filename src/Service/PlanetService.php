<?php

namespace App\Service;

use App\Repository\PlanetRepository;
use App\Repository\PlanetTypeRepository;

readonly class PlanetService
{

    public function __construct(
        protected PlanetRepository     $planetRepository,
        protected PlanetTypeRepository $planetTypeRepository,
    )
    {
    }

    public function getPlanetsByPlayer(
        $user,
        $slug,
    ): array {

        $planets = $this->planetRepository->findByField($user->getUuid());

        $selectedPlanet         = NULL;
        $selectedPlanetTypeData = NULL;
        if($slug !== NULL | empty($slug)) {
            foreach($planets as $planet) {
                if($planet->getSlug() === $slug) {
                    $selectedPlanet = $planet;
                    break;
                }
            }
        }

        if($selectedPlanet === NULL && count($planets) > 0) {
            $selectedPlanet = reset($planets);
        }

        if($selectedPlanet !== NULL) {
            $selectedPlanetTypeData = $this->planetTypeRepository->findByPlanetType($selectedPlanet->getType())[0];
        }

        return [$planets, $selectedPlanet, $selectedPlanetTypeData];
    }

}
