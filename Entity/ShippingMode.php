<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\ShippingModeRepository;
use App\Entity\Shop\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingModeRepository::class)]
class ShippingMode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: PaymentType::class, inversedBy: 'shippingModes')]
    private  $paymentTypes;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'shippingMode')]
    private  $orders;

    #[ORM\ManyToOne(targetEntity: ShopOptions::class, inversedBy: 'paypalShippingMode')]
    private ?ShopOptions $shopOptions = null;

    public function __construct()
    {
        $this->paymentTypes = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PaymentType[]
     */
    public function getPaymentTypes(): Collection
    {
        return $this->paymentTypes;
    }

    public function addPaymentType(PaymentType $paymentType): self
    {
        if (!$this->paymentTypes->contains($paymentType)) {
            $this->paymentTypes[] = $paymentType;
        }

        return $this;
    }

    public function removePaymentType(PaymentType $paymentType): self
    {
        if ($this->paymentTypes->contains($paymentType)) {
            $this->paymentTypes->removeElement($paymentType);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setShippingMode($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getShippingMode() === $this) {
                $order->setShippingMode(null);
            }
        }

        return $this;
    }

    public function getShopOptions(): ?ShopOptions
    {
        return $this->shopOptions;
    }

    public function setShopOptions(?ShopOptions $shopOptions): self
    {
        $this->shopOptions = $shopOptions;

        return $this;
    }
}
