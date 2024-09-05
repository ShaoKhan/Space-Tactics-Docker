<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;

class ResourceService
{


    public function __construct(

    )
    {

    }

    public function saveResourceProduction($data): array
    {
        return ['message' => 'Resource production saved successfully'];
    }
}
