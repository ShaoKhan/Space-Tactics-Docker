<?php

namespace App\Controller;


use App\Entity\Alliance;
use App\Entity\AllianceMember;
use App\Form\AllianceType;
use App\Repository\AllianceMemberRepository;
use App\Repository\AllianceRepository;
use App\Repository\PlanetBuildingRepository;
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
        protected readonly AllianceRepository          $allianceRepository,
        protected readonly AllianceMemberRepository    $allianceMemberRepository,
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
        protected readonly PlanetBuildingRepository    $planetBuildingRepository,
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
        //ToDo: Das scheint noch ziemlich buggy irgendwie.

        $members          = [];
        $planets          = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $actualPlanetId = $planets[1]->getId();
        $canFoundAlliance = FALSE;
        $uniData          = $this->uniRepository->findOneBy(['id' => $this->user->getUni()]);
        $alliance         = $this->allianceRepository->findOneBy(['slug' => $this->user->getAlliance()]) ?? NULL;
        $allianceRank     = $this->allianceMemberRepository->findOneBy(['user_slug' => $this->user->getUuid()]);
        $allianceUuid     = Uuid::v4();

        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(
                [
                    'planet' => $actualPlanetId, 'building' => 1,
                ],
            ),
            $this->planetBuildingRepository->findOneBy(
                [
                    'planet' => $actualPlanetId, 'building' => 2,
                ],
            ),
            $this->planetBuildingRepository->findOneBy(
                [
                    'planet' => $actualPlanetId, 'building' => 3,
                ],
            ),
        );



        if($alliance !== NULL) {
            $allianceUuid = $alliance->getSlug();
            $memberSlugs  = $this->allianceMemberRepository->findBy(['alliance_slug' => $this->user->getAlliance()]);

            foreach($memberSlugs as $memberSlug) {
                $members[] = $this->userRepository->findOneBy(['uuid' => $memberSlug->getUserSlug()]);
            }
        }
        if($this->userService->calculateTotalPoints($this->user) > $uniData->getAllianceMinPoints() && $alliance === NULL) {
            $canFoundAlliance = TRUE;
        }

        $form = $this->createForm(
            AllianceType::class, $alliance,
            [
                'uuid'   => $allianceUuid,
                'update' => $alliance !== NULL,
            ],
        );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Alliance $allianceFromForm */
            $allianceFromForm = $form->getData();
            $allianceFromForm->setSlug($allianceUuid);
            $allianceFromForm->setName($form->get('name')->getData());
            $allianceFromForm->setAllianceTag($form->get('allianceTag')->getData());
            $allianceFromForm->setHeadline($form->get('headline')->getData());
            $allianceFromForm->setDescription($form->get('description')->getData());
            $allianceFromForm->setUrl($form->get('url')->getData());
            $allianceFromForm->setLogo($form->get('logo')->getData());

            if($alliance === NULL) {
                $alliancemember = new AllianceMember();
                $alliancemember->setAllianceSlug($allianceUuid);
                $alliancemember->setUserSlug($this->user->getUuid());
                $alliancemember->setJoinedOn(new \DateTime());
                $alliancemember->setRanking('ROLE_ALLIANCE_FOUNDER');
                $this->allianceMemberRepository->save($alliancemember, TRUE);
            }
            $this->user->setAlliance($allianceUuid);
            $this->allianceRepository->save($allianceFromForm, TRUE);
            $this->userRepository->save($this->user, TRUE);
            $this->addFlash('success', 'Allianz erfolgreich erstellt.');
        }

        return $this->render(
            'alliance/index.html.twig', [
            'planets'          => $planets[0],
            'selectedPlanet'   => $planets[1],
            'planetData'       => $planets[2],
            'user'             => $this->getUser(),
            'messages'         => $this->checkMessagesService->checkMessages(),
            'slug'             => $slug,
            'production'       => $prodActual,
            'form'             => $form->createView(),
            'allianceUuid'     => $allianceUuid,
            'allianceData'     => $alliance,
            'canFoundAlliance' => $canFoundAlliance,
            'allianceRank'     => $allianceRank,
            'members'          => $members,

        ],
        );
    }

    #[Route('/alliance/leave/{slug?}', name: 'leave_alliance')]
    public function leaveAlliance(
        $slug = NULL,
    ): Response {

        //check if user is in this alliance
        $alliance = $this->allianceRepository->findOneBy(['slug' => $this->user->getAlliance()]);
        if($alliance) {
            //check if user is the last one in the alliance
            $member = $this->allianceMemberRepository->findBy(['alliance_slug' => $alliance->getSlug()]);
            if($member->getUserSlug() === $this->user->getUuid() && $member->getRanking() === 'ROLE_ALLIANCE_FOUNDER' && count($member) === 1) {
                $this->allianceRepository->deleteByAllianceSlug($alliance, TRUE);
            }

            $this->user->setAlliance(NULL);
            $this->userRepository->save($this->user, TRUE);
            $this->allianceMemberRepository->deleteByUserSlug($this->user);

            $this->addFlash('success', 'Allianz erfolgreich verlassen.');
        }

        return $this->redirectToRoute('index');

    }

}
