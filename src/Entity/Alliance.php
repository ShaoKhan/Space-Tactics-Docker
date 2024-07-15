<?php

namespace App\Entity;

use App\Repository\AllianceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllianceRepository::class)]
class Alliance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $alliance_ta = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\OneToMany(targetEntity: user::class, mappedBy: 'alliance')]
    private Collection $members;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $headline = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?user $leader = null;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAllianceTa(): ?string
    {
        return $this->alliance_ta;
    }

    public function setAllianceTa(?string $alliance_ta): static
    {
        $this->alliance_ta = $alliance_ta;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(user $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setAlliance($this);
        }

        return $this;
    }

    public function removeMember(user $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getAlliance() === $this) {
                $member->setAlliance(null);
            }
        }

        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): static
    {
        $this->headline = $headline;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLeader(): ?user
    {
        return $this->leader;
    }

    public function setLeader(?user $leader): static
    {
        $this->leader = $leader;

        return $this;
    }
}
