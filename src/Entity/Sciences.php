<?php

namespace App\Entity;

use App\Repository\SciencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SciencesRepository::class)]
class Sciences
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $science_class = null;

    #[ORM\Column]
    private ?int $one_per_planet = null;

    #[ORM\Column]
    private ?int $factor = null;

    #[ORM\Column]
    private ?int $level_max = null;

    #[ORM\Column]
    private ?int $cost_metal = null;

    #[ORM\Column]
    private ?int $cost_crystal = null;

    #[ORM\Column]
    private ?int $cost_deuterium = null;

    #[ORM\Column]
    private ?int $cost_dark_matter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, ScienceDependencies>
     */
    #[ORM\OneToMany(targetEntity: ScienceDependencies::class, mappedBy: 'science')]
    private Collection $scienceDependencies;

    /**
     * @var Collection<int, ShipDependencies>
     */
    #[ORM\OneToMany(targetEntity: ShipDependencies::class, mappedBy: 'requiredScience')]
    private Collection $shipDependencies;

    /**
     * @var Collection<int, UserScience>
     */
    #[ORM\OneToMany(targetEntity: UserScience::class, mappedBy: 'science')]
    private Collection $userSciences;

    private array $properties = [];


    public function __construct()
    {
        $this->scienceDependencies = new ArrayCollection();
        $this->shipDependencies = new ArrayCollection();
        $this->userSciences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getScienceClass(): ?int
    {
        return $this->science_class;
    }

    public function setScienceClass(int $science_class): static
    {
        $this->science_class = $science_class;

        return $this;
    }

    public function getOnePerPlanet(): ?int
    {
        return $this->one_per_planet;
    }

    public function setOnePerPlanet(int $one_per_planet): static
    {
        $this->one_per_planet = $one_per_planet;

        return $this;
    }

    public function getFactor(): ?int
    {
        return $this->factor;
    }

    public function setFactor(int $factor): static
    {
        $this->factor = $factor;

        return $this;
    }

    public function getLevelMax(): ?int
    {
        return $this->level_max;
    }

    public function setLevelMax(int $level_max): static
    {
        $this->level_max = $level_max;

        return $this;
    }

    public function getCostMetal(): ?int
    {
        return $this->cost_metal;
    }

    public function setCostMetal(int $cost_metal): static
    {
        $this->cost_metal = $cost_metal;

        return $this;
    }

    public function getCostCrystal(): ?int
    {
        return $this->cost_crystal;
    }

    public function setCostCrystal(int $cost_crystal): static
    {
        $this->cost_crystal = $cost_crystal;

        return $this;
    }

    public function getCostDeuterium(): ?int
    {
        return $this->cost_deuterium;
    }

    public function setCostDeuterium(int $cost_deuterium): static
    {
        $this->cost_deuterium = $cost_deuterium;

        return $this;
    }

    public function getCostDarkMatter(): ?int
    {
        return $this->cost_dark_matter;
    }

    public function setCostDarkMatter(int $cost_dark_matter): static
    {
        $this->cost_dark_matter = $cost_dark_matter;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, ScienceDependencies>
     */
    public function getScienceDependencies(): Collection
    {
        return $this->scienceDependencies;
    }

    public function addScienceDependency(ScienceDependencies $scienceDependency): static
    {
        if (!$this->scienceDependencies->contains($scienceDependency)) {
            $this->scienceDependencies[] = ($scienceDependency);
            $scienceDependency->setScience($this);
        }

        return $this;
    }

    public function removeScienceDependency(ScienceDependencies $scienceDependency): static
    {
        if ($this->scienceDependencies->removeElement($scienceDependency)) {
            // set the owning side to null (unless already changed)
            if ($scienceDependency->getScience() === $this) {
                $scienceDependency->setScience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShipDependencies>
     */
    public function getShipDependencies(): Collection
    {
        return $this->shipDependencies;
    }

    public function addShipDependency(ShipDependencies $shipDependency): static
    {
        if (!$this->shipDependencies->contains($shipDependency)) {
            $this->shipDependencies->add($shipDependency);
            $shipDependency->setRequiredScience($this);
        }

        return $this;
    }

    public function removeShipDependency(ShipDependencies $shipDependency): static
    {
        if ($this->shipDependencies->removeElement($shipDependency)) {
            // set the owning side to null (unless already changed)
            if ($shipDependency->getRequiredScience() === $this) {
                $shipDependency->setRequiredScience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserScience>
     */
    public function getUserSciences(): Collection
    {
        return $this->userSciences;
    }

    public function addUserScience(UserScience $userScience): static
    {
        if (!$this->userSciences->contains($userScience)) {
            $this->userSciences->add($userScience);
            $userScience->setScience($this);
        }

        return $this;
    }

    public function removeUserScience(UserScience $userScience): static
    {
        if ($this->userSciences->removeElement($userScience)) {
            // set the owning side to null (unless already changed)
            if ($userScience->getScience() === $this) {
                $userScience->setScience(null);
            }
        }

        return $this;
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    public function __get($name)
    {
        return $this->properties[$name] ?? null;
    }
}
