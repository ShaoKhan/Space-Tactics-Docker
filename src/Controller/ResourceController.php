<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResourceController extends CustomAbstractController
{

    public function __construct(
        LoggerInterface $logger,
        Security        $security,
    ) {
        parent::__construct($security, $logger);
    }

    #[Route('/resources', name: 'get_resources')]
    public function getResources(): JsonResponse
    {
        // Beispielhafte Ressourcen-Daten
        $resources = [
            'metal' => 100,
            'crystal' => 50,
            'deuterium' => 25,
        ];

        return new JsonResponse($resources);
    }

}
