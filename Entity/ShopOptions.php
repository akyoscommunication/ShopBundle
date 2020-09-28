<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\ShopOptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopOptionsRepository::class)
 */
class ShopOptions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paypalPayment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $paypalPKey;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $paypalSKey;

    /**
     * @ORM\OneToMany(targetEntity=ShippingMode::class, mappedBy="shopOptions")
     */
    private $paypalShippingMode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypalAccessToken;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $paypalSandbox;

    /**
     * @ORM\Column(type="boolean")
     */
    private $anonymousUsers;

    public function __construct()
    {
        $this->paypalShippingMode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaypalPayment(): ?bool
    {
        return $this->paypalPayment;
    }

    public function setPaypalPayment(bool $paypalPayment): self
    {
        $this->paypalPayment = $paypalPayment;

        return $this;
    }

    public function getPaypalPKey(): ?string
    {
        return $this->paypalPKey;
    }

    public function setPaypalPKey(?string $paypalPKey): self
    {
        $this->paypalPKey = $paypalPKey;

        return $this;
    }

    public function getPaypalSKey(): ?string
    {
        return $this->paypalSKey;
    }

    public function setPaypalSKey(?string $paypalSKey): self
    {
        $this->paypalSKey = $paypalSKey;

        return $this;
    }

    /**
     * @return Collection|ShippingMode[]
     */
    public function getPaypalShippingMode(): Collection
    {
        return $this->paypalShippingMode;
    }

    public function addPaypalShippingMode(ShippingMode $paypalShippingMode): self
    {
        if (!$this->paypalShippingMode->contains($paypalShippingMode)) {
            $this->paypalShippingMode[] = $paypalShippingMode;
            $paypalShippingMode->setShopOptions($this);
        }

        return $this;
    }

    public function removePaypalShippingMode(ShippingMode $paypalShippingMode): self
    {
        if ($this->paypalShippingMode->contains($paypalShippingMode)) {
            $this->paypalShippingMode->removeElement($paypalShippingMode);
            // set the owning side to null (unless already changed)
            if ($paypalShippingMode->getShopOptions() === $this) {
                $paypalShippingMode->setShopOptions(null);
            }
        }

        return $this;
    }

    public function getPaypalAccessToken(): ?string
    {
        return $this->paypalAccessToken;
    }

    public function setPaypalAccessToken(?string $paypalAccessToken): self
    {
        $this->paypalAccessToken = $paypalAccessToken;

        return $this;
    }

    public function getPaypalSandbox(): ?bool
    {
        return $this->paypalSandbox;
    }

    public function setPaypalSandbox(?bool $paypalSandbox): self
    {
        $this->paypalSandbox = $paypalSandbox;

        return $this;
    }

    public function getAnonymousUsers(): ?bool
    {
        return $this->anonymousUsers;
    }

    public function setAnonymousUsers(bool $anonymousUsers): self
    {
        $this->anonymousUsers = $anonymousUsers;

        return $this;
    }
}
