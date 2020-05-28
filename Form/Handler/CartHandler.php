<?php

namespace Akyos\ShopBundle\Form\Handler;

use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\CartItem;
use App\Entity\Product;
use Akyos\ShopBundle\Repository\CartItemRepository;
use Akyos\ShopBundle\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class CartHandler extends AbstractController
{
    private $em;
    /** @var CartItemRepository */
    private $cartItemRepository;
    /** @var CartService */
    private $cartService;

    public function __construct(EntityManagerInterface $entityManager, CartItemRepository $cartItemRepository, CartService $cartService)
    {
        $this->em = $entityManager;
        $this->cartItemRepository = $cartItemRepository;
        $this->cartService = $cartService;
    }

    public function new(Cart $cart): bool
    {
        $this->em->persist($cart);
        $this->em->flush();
        return true;
    }

    public function edit(FormInterface $form, Request $request, Cart $cart): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $cartItemProduct */
            $cartItemProduct = $form->get('product')->getData();
            $cartItemQty = $form->get('qty')->getData();

            $this->cartService->add($cartItemProduct, $cartItemQty, $cart);

            $this->em->flush();

            return true;
        }
        return false;
    }

    public function delete(Cart $cart, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $this->em->remove($cart);
            $this->em->flush();
        }
    }
}
