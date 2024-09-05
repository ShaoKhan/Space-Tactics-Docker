<?php

namespace App\Controller;

use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefenseController extends CustomAbstractController
{

    public function __construct(
        protected readonly PlanetRepository           $planetRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly CheckMessagesService       $checkMessagesService,
        protected readonly PlanetService              $planetService,
        protected readonly ManagerRegistry            $managerRegistry,
        protected readonly PlanetBuildingRepository   $planetBuildingRepository,
        LoggerInterface                               $logger,
        Security                                      $security,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/defense/{slug?}', name: 'defense')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets        = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $actualPlanetId = $planets[1]->getId();

        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
        );


        return $this->render(
            'defense/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->user,
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
        ],
        );
    }
}
