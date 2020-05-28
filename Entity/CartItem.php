<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartItemRepository::class)
 */
class CartItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BaseProduct::class)
     * @ORM\JoinColumn(name="id", referencedColumnName="slug", nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="cartItems", cascade={"persist"})
     */
    private $cart;

    /**
     * @ORM\Column(type="float")
     */
    private $qty;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="cartItems")
     */
    private $orderOfItem;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?BaseProduct
    {
        return $this->product;
    }

    public function setProduct(?BaseProduct $product): self
    {
        $this->product = $product;

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

    public function getQty(): ?float
    {
        return $this->qty;
    }

    public function setQty(float $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getOrderOfItem(): ?Order
    {
        return $this->orderOfItem;
    }

    public function setOrderOfItem(?Order $orderOfItem): self
    {
        $this->orderOfItem = $orderOfItem;

        return $this;
    }

    public function __toString()
    {
        return $this->getProduct()->getName();
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
