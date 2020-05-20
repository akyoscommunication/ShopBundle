<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="orderOfProduct", orphanRemoval=true)
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, inversedBy="OrderOfPayment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\ManyToMany(targetEntity=ShopAddress::class, inversedBy="orders")
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=BaseUserShop::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=ShippingMode::class, inversedBy="orders")
     */
    private $shippingMode;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class, inversedBy="orderOfStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderStatus;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrderOfProduct($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getOrderOfProduct() === $this) {
                $product->setOrderOfProduct(null);
            }
        }

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Collection|ShopAddress[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(ShopAddress $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
        }

        return $this;
    }

    public function removeAddress(ShopAddress $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
        }

        return $this;
    }

    public function getClient(): ?UserShopTrait
    {
        return $this->client;
    }

    public function setClient(?UserShopTrait $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getShippingMode(): ?ShippingMode
    {
        return $this->shippingMode;
    }

    public function setShippingMode(?ShippingMode $shippingMode): self
    {
        $this->shippingMode = $shippingMode;

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }
}
