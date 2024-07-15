<?php

namespace App\Controller;


use App\Entity\Alliance;
use App\Form\AllianceType;
use App\Repository\PlanetRepository;
use App\Repository\UniRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class AllianceController extends CustomAbstractController
{
    public function __construct(
        protected readonly BuildingCalculationService  $buildingCalculationService,
        protected readonly CheckMessagesService        $checkMessagesService,
        protected readonly EmailVerifier               $emailVerifier,
        protected readonly ManagerRegistry             $managerRegistry,
        protected readonly PlanetRepository            $planetRepository,
        protected readonly PlanetService               $planetService,
        protected readonly TranslatorInterface         $translator,
        protected readonly UniRepository               $uniRepository,
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly UserRepository              $userRepository,
        protected readonly UserService                 $userService,
        Security                                       $security,
        LoggerInterface                                $logger,

    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/alliance/{slug?}', name: 'alliance')]
    public function index(
        Request $request,
                $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets    = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        $uniData      = $this->uniRepository->findOneBy(['id' => $this->user->getUni()]);
        $allianceUuid = Uuid::v4();


        if($this->userService->calculateBuildingPoints($this->user) > $uniData->getAllianceMinPoints() && $this->user->getAlliance() === NULL) {
            $this->addFlash('info', 'Du hast die Mindestpunktzahl für eine Allianz erreicht. Bitte bewirb dich bei einer Allianz oder gründe eine eigene.');
        }

        $form = $this->createForm(AllianceType::class, new Alliance(), ['uuid' => $allianceUuid]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render(
            'alliance/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
            'form'           => $form->createView(),
            'allianceUuid'   => $allianceUuid,
        ],
        );
    }
}
