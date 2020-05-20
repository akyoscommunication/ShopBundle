<?php

namespace Akyos\ShopBundle\Service;

use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function add(Cart $cart, Product $product): bool
    {
        $cart->addProduct($product);
        $this->em->flush();
        return true;
    }

    public function update(Cart $cart): bool
    {
    }

    public function remove(Cart $cart, Product $product): bool
    {
        $cart->removeProduct($product);
        $this->em->flush();
        return true;
    }
}