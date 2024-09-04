<?php

namespace Akyos\ShopBundle\Entity;

use Stringable;
use Override;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PaymentTypeRepository::class)]
class PaymentType implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    #[Gedmo\Slug(fields: ['title'], updatable: false)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: ShippingMode::class, mappedBy: 'paymentTypes')]
    private  $shippingModes;

    public function __construct()
    {
        $this->shippingModes = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|ShippingMode[]
     */
    public function getShippingModes(): Collection
    {
        return $this->shippingModes;
    }

    public function addShippingMode(ShippingMode $shippingMode): self
    {
        if (!$this->shippingModes->contains($shippingMode)) {
            $this->shippingModes[] = $shippingMode;
            $shippingMode->addPaymentType($this);
        }

        return $this;
    }

    public function removeShippingMode(ShippingMode $shippingMode): self
    {
        if ($this->shippingModes->contains($shippingMode)) {
            $this->shippingModes->removeElement($shippingMode);
            $shippingMode->removePaymentType($this);
        }

        return $this;
    }

    #[Override]
    public function __toString(): string
    {
        return (string) $this->title;
    }
}
