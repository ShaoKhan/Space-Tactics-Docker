<?php

namespace App\Controller;

use AllowDynamicProperties;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class CustomAbstractController extends SymfonyAbstractController
{
    protected readonly object $user;
    protected readonly string $user_uuid;

    public function __construct(

        protected readonly Security        $security,
        protected readonly LoggerInterface $logger,
    )
    {
        if($security->getUser() !== null) {
            $this->user_uuid = $this->security->getUser()->getUuid();
            $this->user      = $this->security->getUser();
        }
    }

}
