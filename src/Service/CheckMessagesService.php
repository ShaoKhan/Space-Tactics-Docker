<?php

namespace App\Service;

use App\Repository\MessagesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

class CheckMessagesService
{
    public function __construct(
        protected readonly Security $security,
        protected readonly MessagesRepository $messagesRepository,
    )
    {
    }

    public function checkMessages()
    {

        return $this->messagesRepository->findBy(['to_uuid' => $this->security->getUser()->getUuid(), 'deleted' => FALSE]);
    }

    public function checkSupportMessages()
    {

    }

}
