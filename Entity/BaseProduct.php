<?php

namespace Akyos\ShopBundle\Entity;

use Stringable;
use Override;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\MappedSuperclass]
class BaseProduct implements Stringable
{
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'read:cart'])]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'read:cart'])]
    private ?string $slug = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['read', 'read:cart'])]
    private ?float $price = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['read', 'read:cart'])]
    private ?bool $published = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read', 'read:cart'])]
    private ?string $thumbnail = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    #[Override]
    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
