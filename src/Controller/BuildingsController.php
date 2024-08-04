<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\BuildingsQueue;
use App\Entity\Planet;
use App\Repository\BuildingsQueueRepository;
use App\Repository\BuildingsRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\PlanetScienceRepository;
use App\Repository\UniRepository;
use App\Service\BuildingCalculationService;
use App\Service\BuildingDependencyChecker;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


class BuildingsController extends CustomAbstractController
{

    public function __construct(
        protected readonly ManagerRegistry            $managerRegistry,
        protected readonly PlanetRepository           $planetRepository,
        protected readonly PlanetBuildingRepository   $planetBuildingRepository,
        protected readonly BuildingsRepository        $buildingsRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly BuildingDependencyChecker  $buildingDependencyChecker,
        protected readonly BuildingsQueueRepository   $buildingsQueueRepository,
        protected readonly UniRepository              $uniRepository,
        protected readonly PlanetService              $planetService,
        protected readonly CheckMessagesService       $messagesService,
        protected readonly PlanetScienceRepository    $planetScienceRepository,
        LoggerInterface                               $logger,
        Security                                      $security,
    ) {
        parent::__construct($security, $logger);
    }

    /**
     * @throws Exception
     */
    #[Route('/buildings/{slug?}', name: 'buildings')]
    public function index(
        $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if($slug === NULL) {
            throw new Exception('planet slug is null');
        }

        $planets        = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $uni            = $this->uniRepository->findOneBy(['id' => 1]);
        $planet         = $this->planetRepository->findOneBy(['user_uuid' => $this->user->getUuid(), 'slug' => $slug]);
        $actualPlanetId = $planets[1]->getId();
        $buildings      = $this->buildingsRepository->findAll(); //buildings repository
        $buildingList   = [];
        $buildingQueue  = $this->buildingsQueueRepository->findBy(['planet' => $planet]);
        $i              = 0;

        foreach($buildingQueue as $buildQueue) {

            $startDateTime           = new DateTime();
            $endDateTime             = new DateTime($buildQueue->getEndBuild()->format('Y-m-d H:i:s'));
            $timeDifferenceInSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();

            if($timeDifferenceInSeconds >= 0) {
                $buildingList[] = [
                    'buildingId' => $buildQueue->getBuilding()->getSlug(),
                    'name'       => $buildQueue->getBuilding()->getName(),
                    'start'      => $buildQueue->getStartBuild()->format('Y-m-d H:i:s'),
                    'end'        => $buildQueue->getEndBuild()->format('Y-m-d H:i:s'),
                    'timeLeft'   => $timeDifferenceInSeconds,
                ];
            }
        }

        //alle Gebäude die es gibt
        foreach($buildings as $building) {

            $planetBuildings = $this->planetBuildingRepository->findBy(['planet' => $actualPlanetId, 'building' => $building->getId()]);

            $building->__set('nextLevelProd', $this->buildingCalculationService->calculateNextBuildingLevelProduction($building->getId(), $actualPlanetId) * 3600);
            $building->__set('nextLevelBuildCost', $this->buildingCalculationService->calculateNextBuildingCosts($building->getId(), $actualPlanetId));
            $building->__set('nextLevelEnergyCost', $this->buildingCalculationService->calculateNextBuildingLevelEnergyCosts($building->getId(), $actualPlanetId) * 3600);
            $building->__set('obfuscated', base64_encode($building->getSlug() . getenv('OBFUSCATE_SECRET')));
            if($planetBuildings) {
                $building->__set('level', $planetBuildings[0]?->getBuildingLevel());
            }
            $buildings[$i]->__set('isBuildable', $this->buildingDependencyChecker->checkBuildable($building, $planet));
            $this->buildingsRepository->save($building);
            $i++;
        }


        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
        );

        return $this->render(
            'buildings/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->messagesService->checkMessages(),
            'slug'           => $slug,
            'buildings'      => $buildings,
            'production'     => $prodActual,
            'buildList'      => $buildingList,
            'maxQueue'       => $uni->getMaxConstructionCount(),
        ],
        );
    }

    #[NoReturn] #[Route('/saveResource/{slug?}', name: 'save-resource')]
    public function saveResource(
        Request $request,
    ): JsonResponse {

        $data = json_decode($request->getContent(), TRUE);

        $referer = $request->headers->get('referer');
        $referer = explode('/', $referer);
        $slug    = end($referer);

        /** @var Planet $planet */
        $planet = $this->planetRepository->findOneBy(['slug' => $slug]);
        $planet->setMetal(intval($data['amountMetal']));
        $planet->setCrystal(intval($data['amountCrystal']));
        $planet->setDeuterium(intval($data['amountDeuterium']));
        $planet->setLastUpdate(new DateTime());
        $this->planetRepository->save($planet, TRUE);

        return new JsonResponse($data);

    }

    /**
     * @throws Exception
     */
    #[Route('/startConstruction', name: 'start-construction')]
    public function startConstruction(
        Request                    $request,
        Security                   $security,
        BuildingsRepository        $buildingsRepository,
        BuildingsQueueRepository   $buildingsQueueRepository,
        PlanetRepository           $planetRepository,
        PlanetBuildingRepository   $planetBuildingRepository,
        TranslatorInterface        $translator,
        BuildingCalculationService $bcs,
        ManagerRegistry            $managerRegistry,
        UniRepository              $uniRepository,
        EntityManagerInterface     $em,

    ): Response {

        //check if resources are available [done]
        //check if building is buildable by dependency [done before (not visible if not buildable)]
        //check if queue is empty or not full [done]
        // check if building is already in queue [done]
        //start construction [done]
        //update planet resources
        //ToDo: create cronjob to remove from building queue and update energy resources
        $successMessages = [];
        $errorMessages   = [];
        $user            = $security->getUser();
        $planetId        = $request->request->get('planetId');
        $buildingId      = $request->request->get('buildingId');
        $status          = TRUE;

        $actualBuildingData = $planetBuildingRepository->findOneBy(['planet_slug' => $planetId, 'building_slug' => $buildingId]);
        $buildingData       = $buildingsRepository->findOneBy(['slug' => $buildingId]);
        $planet             = $planetRepository->findOneBy(['slug' => $planetId]);
        $uni                = $uniRepository->findOneBy(['id' => $user->getUni()]);

        $metalOnPlanet     = $planet->getMetal();
        $crystalOnPlanet   = $planet->getCrystal();
        $deuteriumOnPlanet = $planet->getDeuterium();

        //build once ?
        if($buildingData->isOnePerPlanet() && $actualBuildingData->getBuildingLevel() >= 1) {
            $errorMessages[] = $translator->trans('only_one_per_planet', [], 'buildings');
            $status          = FALSE;
        }

        //enough resources ?
        $buildCosts = $bcs->calculateNextBuildingCosts($buildingData->getId(), $planet->getId(), $managerRegistry);
        if(($metalOnPlanet < $buildCosts["metal"]) || ($crystalOnPlanet < $buildCosts["crystal"]) || ($deuteriumOnPlanet < $buildCosts["deuterium"])) {
            $errorMessages[] = $translator->trans('not_enough_resources', [], 'buildings');
            $status          = FALSE;
        }

        // building queue is full
        $queue = $buildingsQueueRepository->findBy(['planet' => $planet]);
        if(count($queue) >= $uni->getMaxConstructionCount()) {
            $errorMessages[] = $translator->trans('building_queue', [], 'buildings');
            $status          = FALSE;
        }

        //check if building is already in queue
        foreach($queue as $building) {
            if($building->getBuilding()->getId() === $actualBuildingData->getId()) {
                $errorMessages[] = $translator->trans('already_in_queue', [], 'buildings');
                $status          = FALSE;
            }
        }

        if($status !== FALSE) {
            //calculate new resources
            $newMetal     = $metalOnPlanet - $buildCosts["metal"];
            $newCrystal   = $crystalOnPlanet - $buildCosts["crystal"];
            $newDeuterium = $deuteriumOnPlanet - $buildCosts["deuterium"];
            $planet->setMetal($newMetal)->setCrystal($newCrystal)->setDeuterium($newDeuterium);

            //calculate build time
            $start        = new DateTime();
            $secondsToAdd = (int)(($buildCosts["metal"] + $buildCosts["crystal"] + $buildCosts["deuterium"]) / ($uni->getGameSpeed() * (1 + $planet->getRobotBuilding())) * pow(0.5, $planet->getNaniteBuilding()) * (1 + $uni->getMinBuildTime()) / 60);
            $end          = (clone $start)->add(new DateInterval('PT' . $secondsToAdd . 'S'));

            $buildingQueue = new BuildingsQueue();
            $buildingQueue->setBuilding($buildingData);
            $buildingQueue->setPlanet($planet);
            $buildingQueue->setStartBuild($start);
            $buildingQueue->setEndBuild($end);
            $buildingQueue->setUserSlug($planet->getUserUuid());
            $em->persist($buildingQueue);
            $em->persist($planet);
            $em->flush();

            $successMessages[] = 'Das folgende Gebäude wurde in die Warteschlange eingereiht: ';

        }

        return new JsonResponse(
            [
                'successMessages' => $successMessages,
                'errorMessages'   => $errorMessages,
                'building'        => $translator->trans($buildingData->getName(), [], 'buildings'),
                'end'             => $end ?? NULL,
            ],
        );
    }

}
