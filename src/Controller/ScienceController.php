<?php

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Repository\SciencesRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScienceController extends CustomAbstractController
{

    public function __construct(
        private readonly BuildingCalculationService $buildingCalculationService,
        private readonly PlanetRepository           $planetRepository,
        private readonly SciencesRepository         $sciencesRepository,
        private readonly ManagerRegistry            $managerRegistry,
        protected readonly CheckMessagesService     $checkMessagesService,
        protected readonly PlanetService            $planetService,
        Security                                    $security,
        LoggerInterface                             $logger,

    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/science/{slug?}', name: 'science')]
    public function index(
        $slug = NULL,
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets = $this->planetService->getPlanetsByPlayer($this->user, $slug);

        if($slug === NULL) {
            $slug = $planets[1]->getSlug();
        }

        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        //ToDo: get science by planet and the dependencies
        $sc = $this->sciencesRepository->findAll();

        return $this->render(
            'science/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
            'science'        => $sc,
        ],
        );
    }
}
