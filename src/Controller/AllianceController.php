<?php

namespace App\Controller;


use App\Entity\Alliance;
use App\Form\AllianceType;
use App\Repository\AllianceRepository;
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
use const Exception;

class AllianceController extends CustomAbstractController
{
    public function __construct(
        protected readonly BuildingCalculationService  $buildingCalculationService,
        protected readonly AllianceRepository          $allianceRepository,
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

        $uniData              = $this->uniRepository->findOneBy(['id' => $this->user->getUni()]);
        $allianceUuid         = Uuid::v4();
        $alliance             = $this->allianceRepository->findOneBy(['slug' => $this->user->getAlliance()]) ?? NULL;
        $allowAllianceFounder = FALSE;

        if($this->userService->calculateTotalPoints($this->user) > $uniData->getAllianceMinPoints() && $alliance === NULL) {
            $allowAllianceFounder = TRUE;
        }

        $form = $this->createForm(
            AllianceType::class, $alliance,
            [
                'uuid' => $allianceUuid,
            ],
        );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Alliance $alliance */
            $alliance = $form->getData();
            $alliance->setSlug($allianceUuid);
            $alliance->setName($form->get('name')->getData());
            $alliance->setAllianceTag($form->get('allianceTag')->getData());
            $alliance->setHeadline($form->get('headline')->getData());
            $alliance->setDescription($form->get('description')->getData());
            $alliance->setUrl($form->get('url')->getData());
            $alliance->setLogo($form->get('logo')->getData());

            $this->user->setAlliance($allianceUuid);
            $this->allianceRepository->save($alliance, TRUE);
            $this->userRepository->save($this->user, TRUE);
            $this->addFlash('success', 'Allianz erfolgreich erstellt.');
        }

        return $this->render(
            'alliance/index.html.twig', [
            'planets'              => $planets[0],
            'selectedPlanet'       => $planets[1],
            'planetData'           => $planets[2],
            'user'                 => $this->getUser(),
            'messages'             => $this->checkMessagesService->checkMessages(),
            'slug'                 => $slug,
            'production'           => $prodActual,
            'form'                 => $form->createView(),
            'allianceUuid'         => $allianceUuid,
            'allianceData'         => $alliance,
            'allowAllianceFounder' => $allowAllianceFounder,

        ],
        );
    }

    #[Route('/alliance/leave/{slug?}', name: 'leave_alliance')]
    public function leaveAlliance(
        $slug = NULL,
    ): Response {
        $alliance = $this->allianceRepository->findOneBy(['slug' => $this->user->getAlliance()]);
        if($alliance) {
            $this->user->setAlliance(NULL);
            $this->userRepository->save($this->user, TRUE);
            $this->addFlash('success', 'Allianz erfolgreich verlassen.');
        }

        return $this->redirectToRoute('index');

    }

}
