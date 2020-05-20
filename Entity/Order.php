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
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="orderOfItem")
     */
    private $cartItems;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

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

    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setOrderOfItem($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->contains($cartItem)) {
            $this->cartItems->removeElement($cartItem);
            // set the owning side to null (unless already changed)
            if ($cartItem->getOrderOfItem() === $this) {
                $cartItem->setOrderOfItem(null);
            }
        }

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
}
