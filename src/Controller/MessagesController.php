<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use App\Repository\PlanetBuildingRepository;
use App\Repository\PlanetRepository;
use App\Service\BuildingCalculationService;
use App\Service\CheckMessagesService;
use App\Service\PlanetService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class MessagesController extends CustomAbstractController
{

    use Traits\MessagesTrait;
    use Traits\PlanetsTrait;

    public function __construct(
        protected readonly PlanetRepository           $planetRepository,
        protected readonly BuildingCalculationService $buildingCalculationService,
        protected readonly MessagesRepository         $messagesRepository,
        protected readonly CheckMessagesService       $messagesService,
        protected readonly PlanetBuildingRepository   $planetBuildingRepository,
        protected readonly PlanetService              $planetService,
        Security                                       $security,
        LoggerInterface                                $logger,
    )
    {
        parent::__construct($security, $logger);
    }

    #[Route('/messages/{slug?}', name: 'messages')]
    public function index(
        Request                    $request,
        Security                   $security,
        ManagerRegistry            $managerRegistry,
        PlanetRepository           $p,
        BuildingCalculationService $bcs,
        MessagesRepository         $messagesRepository,
        EntityManagerInterface     $em,
                                   $slug = NULL,
    ): Response {

        $user_uuid = $security->getUser()->getUuid();
        $this->denyAccessUnlessGranted('ROLE_USER');

        $planets        = $this->planetService->getPlanetsByPlayer($this->user, $slug);
        $actualPlanetId = $planets[1]->getId();
        $prodActual     = $this->buildingCalculationService->calculateActualBuildingProduction(
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 1,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 2,],),
            $this->planetBuildingRepository->findOneBy(['planet' => $actualPlanetId, 'building' => 3,],),
        );

        $form = $this->createForm(MessagesType::class, new Messages());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $message = $messagesRepository->findOneBy(['slug' => $form->getData()->getSlug()]);
            $message->setWasRead(TRUE);
            $message->setAnswered(TRUE);

            $messageTo = $form->getData();
            $messageTo->setFromUuid($message->getToUuid());
            $messageTo->setFromName($message->getToName());
            $messageTo->setToUuid($message->getFromUuid());
            $messageTo->setToName($message->getFromName());
            $messageTo->setSendDate(new \DateTime());
            $messageTo->setMessageType($message->getMessageType());
            $messageTo->setSubject('Re: ' . $message->getSubject());
            $messageTo->setWasRead(FALSE);
            $messageTo->setAnswered(FALSE);
            $messageTo->setDeleted(FALSE);
            $messageTo->setSlug(Uuid::v4());

            $em->persist($messageTo);
            $em->flush();
        }


        return $this->render(
            'messages/index.html.twig', [
            'planets'        => $planets[0],
            'selectedPlanet' => $planets[1],
            'user'           => $this->getUser(),
            'messages'       => $this->messagesService->checkMessages(),
            'form'           => $form->createView(),
            'slug'           => $slug,
            'production'     => $prodActual,
        ],
        );
    }

    #[Route('/messages/delete/{slug}', name: 'message_delete')]
    public function message_delete(
        $slug,
        MessagesRepository $messagesRepository,
        EntityManagerInterface $em,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $message = $messagesRepository->findOneBy(['slug' => $slug]);
        $message->setDeleted(TRUE);
        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('messages');
    }

}
