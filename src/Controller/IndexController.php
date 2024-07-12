<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\PlanetRepository;
use App\Repository\UniRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\BuildingCalculationService;
use App\Service\MessagesService;
use App\Service\PlanetService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class IndexController extends CustomAbstractController
{

    use Traits\MessagesTrait;
    use Traits\PlanetsTrait;

    public function __construct(
        protected readonly PlanetService               $planetService,
        protected readonly PlanetRepository            $planetRepository,
        protected readonly MessagesService             $messagesService,
        protected readonly EmailVerifier               $emailVerifier,
        protected readonly ManagerRegistry             $managerRegistry,
        protected readonly BuildingCalculationService  $buildingCalculationService,
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly UniRepository               $uniRepository,
        protected readonly TranslatorInterface         $translator,
        protected readonly UserRepository              $userRepository,
        private readonly RequestStack                  $requestStack,
        Security                                       $security,
        LoggerInterface                                $logger,

    ) {
        parent::__construct($security, $logger);
    }


    #[Route('/', name: 'index')]
    public function indexAction(
        $slug = NULL,
        #[CurrentUser] UserInterface $user = NULL,
    ): Response {
        if($user !== NULL) {

            $planets = $this->planetService->getPlanetsByPlayer($user, $slug);

            // Validate the slug using a regex pattern
            $validSlugPattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}[a-f0-9]$/';
            if($slug === NULL || !preg_match($validSlugPattern, $slug)) {
                $slug = $planets[1]->getSlug();
            }

            // Retrieve planet data
            $planet = $this->planetRepository->findOneBy(['user_uuid' => $user->getUuid(), 'slug' => $slug]);

            // Calculate production
            $production = $this->buildingCalculationService->calculateActualBuildingProduction(
                $planet->getMetalBuilding(),
                $planet->getCrystalBuilding(),
                $planet->getDeuteriumBuilding(),
                $this->managerRegistry,
            );

            // Render the template for authenticated users
            return $this->render(
                'main/index.html.twig',
                [
                    'planets'        => $planets[0],
                    'selectedPlanet' => $planets[1],
                    'planetData'     => $planets[2],
                    'user'           => $user,
                    'messages'       => $this->getMessages($this->security, $this->managerRegistry),
                    'slug'           => $slug,
                    'production'     => $production,
                ],
            );
        }

        return $this->render(
            'index.html.twig',
        );
    }

    #[Route('/news', name: 'news')]
    public function newsIndex(): Response
    {
        return $this->render('news.html.twig');
    }

    #[Route('/rules', name: 'rules')]
    public function rulesIndex(): Response
    {
        return $this->render('rules.html.twig');
    }

    #[Route('/fights', name: 'fights')]
    public function fightsIndex(): Response
    {
        return $this->render('fights.html.twig');
    }

    #[Route('/pillory', name: 'pillory')]
    public function piloryIndex(): Response
    {
        return $this->render('pillory.html.twig');
    }

    #[Route('/imprint', name: 'imprint')]
    public function imprintIndex(): Response
    {
        return $this->render('imprint.html.twig');
    }

    #[Route('/register', name: 'register')]
    public function registerIndex(
        Request $request,
    ): Response {
        $user = new User();

        $form = $this->createForm(
            UserType::class, $user, [
            'action' => $this->generateUrl('register'),
        ],
        );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $planetController = new PlanetController($this->security, $this->logger);
            $userData         = $form->getData();
            $email            = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
            $uuid             = Uuid::v4();

            if($email) {
                $this->addFlash('error', 'Diese E-Mail-Adresse ist bereits vergeben.');
                return $this->redirectToRoute('register');
            }

            /** @var User $userData */
            $userData->setUuid($uuid);
            $userData->setRegisterOn(new \DateTime());
            $hashedPassword = $this->passwordHasher->hashPassword($userData, $userData->getPassword());
            $userData->setPassword($hashedPassword);
            $userData->setRoles(['ROLE_USER']);

            $this->userRepository->save($userData, true);

            $planetData = $planetController->initialPlanetData($this->planetRepository, $this->uniRepository);

            $sys_x = $planetData['system_x'] !== "" ? $planetData['system_x'] : "1";
            $sys_y = $planetData['system_y'] !== "" ? $planetData['system_y'] : "1";
            $sys_z = $planetData['system_z'] !== "" ? $planetData['system_z'] : "1";

            $planet = new Planet();
            $planet->setUserUuid($uuid);
            $planet->setUniverse($user->getUni());
            $planet->setSystemX($sys_x);
            $planet->setSystemY($sys_y);
            $planet->setSystemZ($sys_z);
            $planet->setName($planetData['name']);
            $planet->setType($planetData['type']);
            $planet->setMetal(10000);
            $planet->setCrystal(7500);
            $planet->setDeuterium(5000);
            $planet->setDarkmatter(500);
            $planet->setSlug(Uuid::v4());
            $planetManager = $this->managerRegistry->getManager();
            $planetManager->persist($planet);
            $planetManager->flush();


            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('account@space-tactics.com', 'Space-Tactics Account'))
                    ->to($userData->getEmail())
                    ->subject('Dein Account wurde erstellt')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context(
                        [
                            'username'   => $user->getUsername(),
                            'planetname' => $planetData['name'],
                        ],
                    ),

            );

            $this->addFlash('success', 'Dein Account wurde erfolgreich erstellt.');

        }

        return $this->render(
            'register.html.twig', [
            'form' => $form->createView(),
        ],
        );
    }

    #[Route('/verify/email', name: 'verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        }
        catch(VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('register');
        }
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('index');
    }

    #[Route('/bye', name: 'bye')]
    public function bye()
    {
        return $this->render('logout.html.twig');
    }

}
