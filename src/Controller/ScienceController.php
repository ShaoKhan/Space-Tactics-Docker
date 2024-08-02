<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\PlanetRepository;
use App\Repository\SciencesRepository;
use App\Repository\UserScienceRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use App\Service\ScienceDependencyChecker;
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
        protected readonly UserScienceRepository    $userScienceRepository,
        Security                                    $security,
        LoggerInterface                             $logger, private readonly ScienceDependencyChecker $scienceDependencyChecker,

    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/science/{slug?}', name: 'science')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $planets  = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $planet = $this->planetRepository->findOneBy(['user_uuid' => $this->user->getUuid(), 'slug' => $slug]);
        $sciences = $this->sciencesRepository->findAll();
        $i        = 0;

        if($slug === NULL) {
            $slug = $planets[1]->getSlug();
        }

        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($planet->getMetalBuilding(), $planet->getCrystalBuilding(), $planet->getDeuteriumBuilding());

        foreach($sciences as $science) {
            $sciences[$i]->__set('isResearchable', $this->scienceDependencyChecker->checkResearchable($science, $planet));
            $this->sciencesRepository->save($science);
            $i++;
        }

        return $this->render(
            'science/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
            'sciences'       => $sciences,
        ],
        );
    }
}
