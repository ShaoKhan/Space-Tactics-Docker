<?php

namespace App\Entity;

use App\Repository\UserScienceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserScienceRepository::class)]
class UserScience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSciences')]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'userSciences')]
    private ?Sciences $science = null;

    #[ORM\Column(nullable: true)]
    private ?int $scienceLevel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getScience(): ?Sciences
    {
        return $this->science;
    }

    public function setScience(?Sciences $science): static
    {
        $this->science = $science;

        return $this;
    }

    public function getScienceLevel(): ?int
    {
        return $this->scienceLevel;
    }

    public function setScienceLevel(?int $scienceLevel): static
    {
        $this->scienceLevel = $scienceLevel;

        return $this;
    }
}
