<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Repository\ShipsRepository;
use App\Repository\UserScienceRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use App\Service\ShipDependencyChecker;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShipyardController extends CustomAbstractController
{

    public function __construct(
        protected readonly PlanetRepository           $planetRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly CheckMessagesService       $checkMessagesService,
        protected readonly PlanetService              $planetService,
        protected readonly ManagerRegistry            $managerRegistry,
        protected readonly ShipsRepository            $shipsRepository,
        protected readonly ShipDependencyChecker      $shipDependencyChecker,
        protected readonly UserScienceRepository      $userScienceRepository,
        LoggerInterface                               $logger,
        Security                                      $security,
    ) {
        parent::__construct($security, $logger);
    }


    #[Route('/shipyard/{slug?}', name: 'shipyard')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets    = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $planet     = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $ships      = $this->shipsRepository->findAll();
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($planet->getMetalBuilding(), $planet->getCrystalBuilding(), $planet->getDeuteriumBuilding());
        $i          = 0;

        foreach($ships as $ship) {
            $ships[$i]->__set('isResearchable', $this->shipDependencyChecker->checkResearchable($ship, $planet));
            $this->shipsRepository->save($ship);
            $i++;
        }

        return $this->render(
            'shipyard/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->user,
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
            'ships'          => $ships,
        ],
        );
    }
}
