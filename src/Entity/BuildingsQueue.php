<?php

namespace App\Entity;

use App\Repository\BuildingsQueueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingsQueueRepository::class)]
class BuildingsQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = NULL;

    #[ORM\Column(length: 255)]
    private ?string $planet_slug;

    #[ORM\Column(length: 255)]
    private ?string $building_slug;

    #[ORM\Column(length: 255)]
    private ?string $user_slug = NULL;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_build = NULL;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_build = NULL;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanet(): ?string
    {
        return $this->planet_slug;
    }

    public function setPlanet(?string $planet_slug): self
    {
        $this->planet_slug = $planet_slug;
        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building_slug;
    }

    public function setBuilding(?string $building_slug): self
    {
        $this->building_slug = $building_slug;
        return $this;
    }

    public function getUserSlug(): ?string
    {
        return $this->user_slug;
    }

    public function setUserSlug(string $user_slug): self
    {
        $this->user_slug = $user_slug;

        return $this;
    }

    public function getStartBuild(): ?\DateTimeInterface
    {
        return $this->start_build;
    }

    public function setStartBuild(\DateTimeInterface $start_build): self
    {
        $this->start_build = $start_build;

        return $this;
    }

    public function getEndBuild(): ?\DateTimeInterface
    {
        return $this->end_build;
    }

    public function setEndBuild(\DateTimeInterface $end_build): self
    {
        $this->end_build = $end_build;

        return $this;
    }
}
