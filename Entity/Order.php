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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=ShopAddress::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $invoiceAddress;

    /**
     * @ORM\ManyToOne(targetEntity=ShopAddress::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryAddress;

    /**
     * @ORM\OneToOne(targetEntity=Cart::class, inversedBy="orderOfCart", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClient(): ?BaseUserShop
    {
        return $this->client;
    }

    public function setClient(?BaseUserShop $client): self
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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getInvoiceAddress(): ?ShopAddress
    {
        return $this->invoiceAddress;
    }

    public function setInvoiceAddress(?ShopAddress $invoiceAddress): self
    {
        $this->invoiceAddress = $invoiceAddress;

        return $this;
    }

    public function getDeliveryAddress(): ?ShopAddress
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?ShopAddress $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}
