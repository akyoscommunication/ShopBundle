<?php

namespace Akyos\ShopBundle\Service\Cart;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\CartItem;
use Akyos\ShopBundle\Repository\ShopOptionsRepository;
use App\Entity\Shop\Product;
use Akyos\ShopBundle\Repository\CartItemRepository;
use Akyos\ShopBundle\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CartService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var BaseUserShop */
    private $user;
    /** @var CartRepository */
    private $cartRepository;
    /** @var SessionInterface */
    private $session;
    /** @var CartItemRepository */
    private $cartItemRepository;
    private $shopOptions;

    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        SessionInterface $session,
        ShopOptionsRepository $shopOptionsRepository
    )
    {
        $this->em = $em;
        $this->user = $security->getUser();
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->session = $session;
        $this->shopOptions = $shopOptionsRepository->findAll()[0];
    }

    public function getCart($token = null)
    {
        /** @var Cart $cartSession */
        $cartSession = ($this->session->get('panier') ? $this->cartRepository->find($this->session->get('panier')) : null);

        if ($this->user && $this->user instanceof BaseUserShop) {
            $ifCartExist = $this->shopOptions->getAnonymousUsers()
                ? $this->cartRepository->findOneBy(['token' => $token, 'isSaved' => false])
                : $this->cartRepository->findOneBy(['client' => $this->user->getId(), 'isSaved' => false]);

            if ($cartSession) {
                if ($ifCartExist) {
                    foreach ($ifCartExist->getCartItems() as $cartItem) {
                        $ifCartExist->removeCartItem($cartItem);
                        $this->em->remove($cartItem);
                    }
                    $this->em->remove($ifCartExist);

                    $cartSession->setClient($this->user);
                    $this->em->flush();
                }
                return $cartSession;
            } else {
                if ($ifCartExist) {
                    return $ifCartExist;
                } else {
                    $newCart = new Cart();
                    $newCart->setClient($this->user);
                    $newCart->setIsSaved(false);
                    $this->em->persist($newCart);
                    $this->em->flush();

                    $this->session->set('panier', $newCart->getId());

                    return $newCart;
                }
            }
        } else {
            if ($cartSession) {
                return $cartSession;
            } else {
                $newCart = new Cart();
                $newCart->setIsSaved(false);

                if ($token) {
                    $newCart->setToken($token);
                }

                $this->em->persist($newCart);
                $this->em->flush();

                $this->session->set('panier', $newCart->getId());

                return $newCart;
            }
        }
    }

    public function add(Product $product, $qty, Cart $cart = null)
    {
        $cart = ($cart ?: $this->getCart());
        $existInCart = $this->cartItemRepository->findOneBy(['cart' => $cart, 'product' => $product]);

        if ($existInCart) {
            $existInCart->setQty($existInCart->getQty() + $qty);
            $this->em->flush();
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setPrice($product->getPrice());
            $cartItem->setProduct($product);
            $cartItem->setQty($qty);
            $cart->addCartItem($cartItem);
            $this->em->persist($cartItem);
            $this->em->flush();
        }

    }

    public function update(CartItem $cartItem, int $qty)
    {
        $cartItem->setQty($qty);
        $this->em->flush();
    }

    public function remove(CartItem $cartItem)
    {
        $this->getCart()->removeCartItem($cartItem);
        $this->em->flush();
    }

    public function getTotal(Cart $cart = NULL) : int
    {
        $cart = ($cart ?: $this->getCart());
        $total = 0;

        foreach ($cart->getCartItems() as $cartItem) {
            $total += $cartItem->getPrice() * $cartItem->getQty();
        }

        return $total;
    }

    public function getTotalQty(Cart $cart = NULL)
    {
        $cart = ($cart ?: $this->getCart());
        $total = 0;

        foreach ($cart->getCartItems() as $cartItem) {
            $total += $cartItem->getQty();
        }

        return $total;
    }
}