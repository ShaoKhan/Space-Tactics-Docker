<?php

namespace App\Controller;

use App\Entity\Support;
use App\Entity\User;
use App\Form\SupportAnswerType;
use App\Form\SupportType;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Repository\SupportRepository;
use App\Repository\UniRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class MainController extends CustomAbstractController
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
        protected readonly UserService                 $userService,
        Security                                       $security,
        LoggerInterface                                $logger,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/main/{slug?}', name: 'main')]
    public function index(
        $slug,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets        = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $actualPlanetId = $planets[1]->getId();

        if($slug === NULL) {
            $slug = $planets[1]->getSlug();
        }

        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
        );
        $now        = new \DateTime();
        $nowString  = $now->format('Y-m-d H:i:s');

        //ToDo
        // 1. get all buildings in queue


        return $this->render(
            'main/index.html.twig', [
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

    #[Route('/app_logout', name: 'app_logout')]
    public function logoutAction(
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface              $session,
        RequestStack                  $requestStack,
        Security                      $security,
    ): Response {

        if($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Custom logout logic, if needed
            /** @var User $user */
            $user = $this->user;
            $user->setLogoutOn(new \DateTime());
            $this->userRepository->save($user);
            $requestStack->getSession()->invalidate();

        }
        $security->logout();
        $session->invalidate();

        return $this->render('logout.html.twig');

    }

    #[Route('/statistics/{slug?}', name: 'statistics')]
    public function statistics(
        $slug,

    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets            = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $actualPlanetId     = $planets[1]->getId();
        $slug               = $slug ?? $planets[0]->getSlug();
        $prodActual         = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
        );
        $planetForBuildings = $this->planetRepository->findBy(['user_uuid' => $this->user_uuid]);
        $buildings          = [];

        foreach($planetForBuildings as $pl) {
            $buildings[$pl->getSlug()] = $this->planetBuildingRepository->findBy(['planet' => $pl]);
            #$buildings[$pl->getSlug()] = $this->planetBuildingRepository->getPlanetBuildingsByPlanetId($this->em, $pl->getId());
        }

        return $this->render(
            'main/statistics.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'planetData'     => $planets[2],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'slug'           => $slug,
            'production'     => $prodActual,
            'buildings'      => $buildings,
        ],
        );
    }

    #[Route('/support/{slug?}', name: 'support')]
    public function support(
        Request $request,
        Session $session,
                $slug = NULL,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets = $this->planetService->getPlanetsByPlayer($this->user, $slug);

        if($slug === NULL) {
            $slug = $planets[1]->getSlug();
        }

        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        $tickets = $this->supportRepository->findBy(['uuid' => $this->user_uuid, 'closed' => 0]);

        $groupedMessages = [];
        foreach($tickets as $message) {
            $parentId = $message->getParentMessage();
            if(!isset($parentId)) {
                $groupedMessages[$message->getId()] = [
                    'question' => $message,
                    'answers'  => [],
                ];
            } else {
                $groupedMessages[$parentId]['answers'][] = $message;
            }
        }

        $answerForm = $this->createForm(SupportAnswerType::class);
        $answerForm->handleRequest($request);

        if($answerForm->isSubmitted() && $answerForm->isValid()) {
            $old       = $this->supportRepository->findOneBy(['slug' => $answerForm->get('ticketId')->getData()]);
            $newTicket = new Support();

            $newTicket->setUuid($this->user_uuid)
                      ->setDatum(new \DateTime())
                      ->setSubject('RE: ' . $old->getSubject())
                      ->setTheme($old->getTheme())
                      ->setMessage($answerForm->get('message')->getData())
                      ->setProcessedBy($this->user->getUsername())
                      ->setProcessedSince(new \DateTime())
                      ->setAnswered(1)
                      ->setClosed(0)
                      ->setParentMessage($old->getId())
                      ->setUsername($this->user->getUsername())
                      ->setSlug(Uuid::v4());

            $this->supportRepository->save($newTicket);
        }


        $form = $this->createForm(SupportType::class, new Support());
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $form->getData()->setUuid($this->user_uuid);
            $form->getData()->setUsername($this->user->getUsername());
            $form->getData()->setDatum(new \DateTime());
            $form->getData()->setAnswered(FALSE);
            $form->getData()->setClosed(FALSE);
            $form->getData()->setSlug(Uuid::v4());

            $this->supportRepository->save($form->getData());
            $session->getFlashBag()->add('success', 'Dein Ticket wurde erstellt. Vielen Dank.');
        }
        return $this->render(
            'main/support.html.twig', [
            'selectedPlanet'  => $planets[1],
            'user'            => $this->getUser(),
            'messages'        => $this->checkMessagesService->checkMessages(),
            'form'            => $form->createView(),
            'answerForm'      => $answerForm->createView(),
            'tickets'         => $tickets,
            'slug'            => $slug,
            'production'      => $prodActual,
            'groupedMessages' => $groupedMessages,
        ],
        );
    }


    /*#[Route('/rules/{slug?}', name: 'rules')]
    public function rules(
        Request $request, ManagerRegistry $managerRegistry, PlanetRepository $p, EntityManagerInterface $em, Security $security, $slug = NULL,
    ): Response
    {
        $planets = $this->getPlanetsByPlayer($managerRegistry, $this->user_uuid, $slug);

        return $this->render(
            'main/rules.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'user'           => $this->getUser(),
            'messages'       => $this->getMessages($security, $managerRegistry),
            'slug'           => $slug,
        ],
        );
    }*/

    #[Route('/notices/{slug?}', name: 'notices')]
    public function playerNotices(
        $slug = NULL,
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $planets = $this->planetService->getPlanetsByPlayer($this->user, $slug);

        if($slug === NULL) {
            $slug = $planets[1]->getSlug();
        }

        $res        = $this->planetRepository->findOneBy(['user_uuid' => $this->user_uuid, 'slug' => $slug]);
        $prodActual = $this->buildingCalculationService->calculateActualBuildingProduction($res->getMetalBuilding(), $res->getCrystalBuilding(), $res->getDeuteriumBuilding(), $this->managerRegistry);

        return $this->render(
            'main/notices.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'user'           => $this->getUser(),
            'messages'       => $this->checkMessagesService->checkMessages(),
            'production'     => $prodActual,
            'slug'           => $slug,
        ],
        );
    }

    #[Route('/support/ticket_close/{ticketId}/{slug?}', name: 'ticket_close')]
    public function closeTicket(
        int $ticketId,
            $slug = NULL,
    ): Response {
        $ticket = $this->supportRepository->find($ticketId);
        $ticket->setClosed(1);
        $this->supportRepository->save($ticket);

        return $this->redirectToRoute(
            'support',
            [
                'slug' => $slug,
            ],
        );
    }

    #[Route('/support/ticket_answer/{ticketId}/{slug?}', name: 'ticket_answer')]
    public function answerTicket(
        int                 $ticketId,
        #[CurrentUser] User $user,
                            $slug = NULL,
    ) {
        $ticket = $this->supportRepository->find($ticketId);
        $ticket->setParentMessage($ticket->getSlug());
        $ticket->setProcessedBy($user->getUsername());
        #$ticket->setClosed(1);
        $this->supportRepository->save($ticket);

        return $this->redirectToRoute(
            'support',
            [
                'slug' => $slug,
            ],
        );
    }

}
