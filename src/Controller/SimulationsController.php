<?php

namespace App\Controller;

use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\SupportRepository;
use App\Repository\UniRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SimulationsController extends CustomAbstractController
{

    public function __construct(
        protected readonly PlanetService               $planetService,
        protected readonly PlanetRepository            $planetRepository,
        protected readonly CheckMessagesService        $checkMessagesService,
        protected readonly EmailVerifier               $emailVerifier,
        protected readonly ManagerRegistry             $managerRegistry,
        protected readonly BuildingCalculationService  $buildingCalculationService,
        protected readonly PlanetBuildingRepository    $planetBuildingRepository,
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly EntityManagerInterface      $em,
        protected readonly UniRepository               $uniRepository,
        protected readonly TranslatorInterface         $translator,
        protected readonly UserRepository              $userRepository,
        protected readonly SupportRepository           $supportRepository,
        Security                                       $security,
        LoggerInterface                                $logger,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/simulations/{slug?}', name: 'simulations')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $planets    = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        return $this->render(
            'simulations/index.html.twig', [
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
