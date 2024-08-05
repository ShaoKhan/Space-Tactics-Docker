<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\UserRepository;
use App\Service\BuildingCalculationService;
use App\Service\PlanetService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginListener implements AuthenticationSuccessHandlerInterface
{

    use TargetPathTrait;

    public function __construct(
        protected readonly EntityManagerInterface     $entityManager,
        protected readonly ManagerRegistry            $managerRegistry,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly UserRepository             $userRepository,
        protected readonly PlanetBuildingRepository   $planetBuildingRepository,
        protected readonly PlanetRepository           $planetRepository,
        protected readonly PlanetService              $planetService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(
        Request        $request,
        TokenInterface $token,
    ): ?Response
    {

        /** @var User $user */
        $user = $token->getUser();


        #if($user instanceof User) {

        $user->setLoginOn(new DateTime());
        $this->userRepository->save($user);

        $lastLogout     = $user->getLogoutOn() ?? new DateTime();
        $planets        = $this->planetRepository->findBy(['user_uuid' => $user->getUuid()]);
        $actualPlanetId = $planets[1]->getId();
        $now            = new DateTime();

        foreach($planets as $planet) {
            $interval = $lastLogout->diff($now);
            $seconds  = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400;

            if($seconds > 0) {
                $buildingProd = $this->buildingCalculationService->calculateActualBuildingProduction(
                    $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
                    $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
                    $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
                );

                $metal     = $buildingProd[0] * $seconds;
                $crystal   = $buildingProd[1] * $seconds;
                $deuterium = $buildingProd[2] * $seconds;

                $planet->setMetal($planet->getMetal() + $metal);
                $planet->setCrystal($planet->getCrystal() + $crystal);
                $planet->setDeuterium($planet->getDeuterium() + $deuterium);
            }
            $this->planetRepository->save($planet);
        }
        #$this->planetRepository->save($planet);

        #}
        return NULL;
    }
}
