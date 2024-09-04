<?php

namespace Akyos\ShopBundle\Entity;

use Stringable;
use Override;
use Akyos\ShopBundle\Repository\CartItemRepository;
use App\Entity\Shop\Product;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem implements Stringable
{

	#[Groups(['read:cart'])]
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private $id;

	#[ORM\ManyToOne(targetEntity: Product::class)]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['read:cart'])]
	private ?Product $product = null;

	#[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems', cascade: ['persist'])]
	private ?Cart $cart = null;

	#[ORM\Column(type: 'float')]
	#[Groups(['read:cart'])]
	private ?float $qty = null;

	#[ORM\Column(type: 'float')]
	#[Groups(['read:cart'])]
	private ?float $price = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getProduct(): ?Product
	{
		return $this->product;
	}

	public function setProduct(?Product $product): self
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

	#[Override]
	public function __toString(): string
	{
		return (string)$this->getProduct()->getName();
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
