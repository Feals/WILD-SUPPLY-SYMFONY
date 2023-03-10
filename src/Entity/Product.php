<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;
use DateTime;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable:true)]
    private ?string $photo = null;

    #[Vich\UploadableField(mapping: 'photo_file', fileNameProperty: 'photo')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $photoFile = null;

    #[ORM\Column(length: 255)]
    private ?string $statusSold = "en vente";

    #[ORM\Column]
    private array $material = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private array $categoryRoom = [];

    #[ORM\Column]
    private array $color = [];

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryItem $categoryItem = null;

    #[ORM\Column]
    private ?bool $showPhoneUser = false;

    #[ORM\Column]
    private ?bool $showEmailUser = false;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Cart $cart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getStatusSold(): ?string
    {
        return $this->statusSold;
    }

    public function setStatusSold(string $statusSold): self
    {
        $this->statusSold = $statusSold;

        return $this;
    }

    public function getMaterial(): array
    {
        return $this->material;
    }

    public function setMaterial(array $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCategoryRoom(): array
    {
        return $this->categoryRoom;
    }

    public function setCategoryRoom(array $categoryRoom): self
    {
        $this->categoryRoom = $categoryRoom;

        return $this;
    }

    public function getColor(): array
    {
        return $this->color;
    }

    public function setColor(array $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategoryItem(): ?CategoryItem
    {
        return $this->categoryItem;
    }

    public function setCategoryItem(?CategoryItem $categoryItem): self
    {
        $this->categoryItem = $categoryItem;

        return $this;
    }

    public function isShowPhoneUser(): ?bool
    {
        return $this->showPhoneUser;
    }

    public function setShowPhoneUser(bool $showPhoneUser): self
    {
        $this->showPhoneUser = $showPhoneUser;

        return $this;
    }

    public function isShowEmailUser(): ?bool
    {
        return $this->showEmailUser;
    }

    public function setShowEmailUser(bool $showEmailUser): self
    {
        $this->showEmailUser = $showEmailUser;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function setPhotoFile(?File $image = null): void
    {
        $this->photoFile = $image;
        if (null !== $image) {
            $this->updatedAt = new DateTime('now');
        }
    }


    public function getphotoFile(): ?File
    {
        return $this->photoFile;
    }

/**
 * Get the value of updatedAt
 */
    public function getUpdatedAt(): DateTimeInterface|null
    {
        return $this->updatedAt;
    }

/**
 * Set the value of updatedAt
 *
 * @return  self
 */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
