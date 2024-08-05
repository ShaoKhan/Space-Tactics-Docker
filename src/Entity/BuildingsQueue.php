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
    private ?int $id = null;

    ##[ORM\ManyToOne(inversedBy: 'building')]
    ##[ORM\JoinColumn(nullable: false)]
    #private ?Planet $planet = null;
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $planet = null;


//    #[ORM\ManyToOne(inversedBy: 'buildingsQueues')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?Buildings $building = null;
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $building = null;

    #[ORM\Column(length: 255)]
    private ?string $user_slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_build = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_build = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanet(): ?string
    {
        return $this->planet;
    }

    public function setPlanet(?string $planet): self
    {
        $this->planet = $planet;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): self
    {
        $this->building = $building;

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
