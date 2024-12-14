<?php

namespace App\Entity;

use App\Repository\AllianceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: AllianceRepository::class)]
class Alliance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = NULL;

    #[ORM\Column(length: 255)]
    private ?string $slug = NULL;

    #[ORM\Column(length: 255)]
    private ?string $name = NULL;

    #[ORM\Column(length: 5, nullable: TRUE)]
    private ?string $alliance_tag = NULL;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'alliance')]
    private Collection $members;

    #[ORM\Column(length: 255, nullable: TRUE)]
    private ?string $headline = NULL;

    #[ORM\Column(type: Types::TEXT, nullable: TRUE)]
    private ?string $description = NULL;

    #[ORM\Column(length: 255, nullable: TRUE)]
    private ?string $url = NULL;

    #[Vich\UploadableField(mapping: 'alliance', fileNameProperty: 'logoName', size: 'logoSize')]
    private ?File $logo = NULL;

    #[ORM\Column(nullable: TRUE)]
    private ?string $logoName = NULL;

    #[ORM\Column(nullable: TRUE)]
    private ?int $logoSize = NULL;

    #[ORM\Column(nullable: TRUE)]
    private ?DateTimeImmutable $updatedAt = NULL;


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

    public function getAllianceTag(): ?string
    {
        return $this->alliance_tag;
    }

    public function setAllianceTag(?string $alliance_tag): static
    {
        $this->alliance_tag = $alliance_tag;

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

    public function getLogo(): ?File
    {
        return $this->logo;
    }

    public function setLogo(?File $logo = NULL): void
    {
        $this->logo = $logo;

        if(NULL !== $logo) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): void
    {
        $this->logoName = $logoName;
    }

    public function getLogoSize(): ?int
    {
        return $this->logoSize;
    }

    public function setLogoSize(?int $logoSize): void
    {
        $this->logoSize = $logoSize;
    }

}
