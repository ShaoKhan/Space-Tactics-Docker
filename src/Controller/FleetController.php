<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FleetController extends CustomAbstractController
{
    public function __construct(
        protected readonly PlanetRepository           $planetRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly CheckMessagesService       $checkMessagesService,
        protected readonly PlanetService              $planetService,
        protected readonly ManagerRegistry            $managerRegistry,
        LoggerInterface                               $logger,
        Security                                      $security,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/fleet/{slug?}', name: 'fleet')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets    = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        return $this->render(
            'fleet/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
        ],
        );
    }
}
