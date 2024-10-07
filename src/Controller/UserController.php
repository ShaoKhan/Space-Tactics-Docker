<?php

namespace App\Controller;

use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\UserRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends CustomAbstractController
{
    public function __construct(
        protected readonly PlanetRepository           $planetRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly CheckMessagesService       $checkMessagesService,
        protected readonly PlanetService              $planetService,
        protected readonly ManagerRegistry            $managerRegistry,
        protected readonly PlanetBuildingRepository   $planetBuildingRepository,
        protected readonly UserRepository             $userRepository,
        protected readonly UserService                $userService,
        LoggerInterface                               $logger,
        Security                                      $security,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('account-settings', name: 'account_settings')]
    public function accountSettingsAction(): Response
    {
        $user           = $this->getUserEntity();
        $planets        = $this->getPlanetsByUser($user);
        $actualPlanetId = $planets[1]->getId();
        $prodActual     = $this->calculateProduction($actualPlanetId);

        $alliance = $this->userService->getAllianceByUuid($user->getAlliance());

        return $this->render(
            'user/account_settings.html.twig',
            [
                'planets'        => $planets[0],
                'selectedPlanet' => $planets[1],
                'planetData'     => $planets[2],
                'messages'       => $this->checkMessagesService->checkMessages(),
                'slug'           => $user->getUuid(),
                'production'     => $prodActual,
                'user'           => $user,
                'alliance'       => $alliance ?? null,
            ],
        );
    }

    private function getUserEntity()
    {
        return $this->userRepository->findOneBy(['uuid' => $this->user->getUuid()]);
    }

    private function getPlanetsByUser($user): array
    {
        return $this->planetService->getPlanetsByPlayer($this->user, $user->getUuid());
    }

    private function calculateProduction($actualPlanetId): array
    {
        return $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1]),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2]),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3]),
        );
    }

    #[Route('update-user-status', name: 'update_user_status')]
    public function setVacation(
        Request $request,
    ): JsonResponse|Response {
        $this->isGranted('ROLE_USER');
        $vacationStatus       = (int)$request->get('status');
        $actualVacationStatus = $this->userRepository->findOneBy(['uuid' => $this->user->getUuid()])->getVacation();

        if($vacationStatus === $actualVacationStatus) {
            return $this->json(['status' => 'error', 'message' => 'Das geht so nicht.']);
        }

        try {
            $this->userRepository->updateVacationStatus($this->user->getUuid(), $vacationStatus);
            return $this->json(['status' => 'success', 'message' => 'Der Urlaubsstatus wurde erfolgreich geändert.']);
        }
        catch(Exception) {
            return $this->json(['status' => 'error', 'message' => 'Es ist ein Fehler aufgetreten.']);
        }
    }


}
