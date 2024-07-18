<?php

namespace App\Entity;

use App\Repository\AllianceMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllianceMemberRepository::class)]
class AllianceMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alliance_slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $joined_on = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ranking = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAllianceSlug(): string
    {
        return $this->alliance_slug;
    }

    public function setAllianceSlug(string $alliance_slug): static
    {
        $this->alliance_slug = $alliance_slug;

        return $this;
    }

    public function getUserSlug(): string
    {
        return $this->user_slug;
    }

    public function setUserSlug(string $user_slug): static
    {
        $this->user_slug = $user_slug;

        return $this;
    }

    public function getJoinedOn(): ?\DateTimeInterface
    {
        return $this->joined_on;
    }

    public function setJoinedOn(?\DateTimeInterface $joined_on): static
    {
        $this->joined_on = $joined_on;

        return $this;
    }

    public function getRanking(): ?string
    {
        return $this->ranking;
    }

    public function setRanking(?string $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }
}
