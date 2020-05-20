<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\CoreBundle\Entity\BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @MappedSuperclass
 * @ORM\Entity(repositoryClass="Akyos\ShopBundle\Repository\BaseUserShopRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class BaseUserShop extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Akyos\ShopBundle\Entity\Order", mappedBy="client")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="Akyos\ShopBundle\Entity\ShopAddress", mappedBy="client", orphanRemoval=true)
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Akyos\ShopBundle\Entity\Cart", mappedBy="client", orphanRemoval=true)
     */
    private $carts;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $order->setClient($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ShopAddress[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(ShopAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setClient($this);
        }

        return $this;
    }

    public function removeAddress(ShopAddress $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getClient() === $this) {
                $address->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setClient($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->contains($cart)) {
            $this->carts->removeElement($cart);
            // set the owning side to null (unless already changed)
            if ($cart->getClient() === $this) {
                $cart->setClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->email;
    }
}
