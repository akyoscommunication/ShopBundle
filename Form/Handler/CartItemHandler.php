<?php

namespace Akyos\ShopBundle\Form\Handler;

use Akyos\ShopBundle\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class CartItemHandler extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function new(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cartItem = $form->getData();
            $this->em->persist($cartItem);
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function edit(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CartItem $cartItem */
            $cartItem = $form->getData();

            if ($cartItem->getQty() <= 0) {
                $this->em->remove($cartItem);
            }

            $this->em->flush();
            return true;
        }
        return false;
    }

    public function delete(CartItem $cartItem, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $this->em->remove($cartItem);
            $this->em->flush();
        }
    }
}
