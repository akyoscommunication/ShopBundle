<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\CartRepository;
use App\Entity\Shop\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:cart"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSaved;

    /**
     * @ORM\ManyToOne(targetEntity=BaseUserShop::class, inversedBy="carts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="cart", orphanRemoval=true)
     * @Groups({"read:cart"})
     */
    private $cartItems;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="cart", cascade={"persist", "remove"})
     */
    private $orderOfCart;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsSaved(): ?bool
    {
        return $this->isSaved;
    }

    public function setIsSaved(bool $isSaved): self
    {
        $this->isSaved = $isSaved;

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
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->contains($cartItem)) {
            $this->cartItems->removeElement($cartItem);
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getOrderOfCart(): ?Order
    {
        return $this->orderOfCart;
    }

    public function setOrderOfCart(Order $orderOfCart): self
    {
        $this->orderOfCart = $orderOfCart;

        // set the owning side of the relation if necessary
        if ($orderOfCart->getCart() !== $this) {
            $orderOfCart->setCart($this);
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
