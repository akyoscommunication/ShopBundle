<?php

namespace Akyos\ShopBundle\Form\Handler;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseUserShopHandler extends AbstractController
{
    private $em;
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function new(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BaseUserShop $baseUserShop */
            $baseUserShop = $form->getData();
            $baseUserShop->setPassword(
                $this->passwordEncoder->encodePassword(
                    $baseUserShop,
                    $form->get('password')->getData()
                )
            );
            $baseUserShop->setRoles(['ROLE_CUSTOMER']);
            $this->em->persist($baseUserShop);
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function edit(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return true;
        }
        return false;
    }

    public function delete(BaseUserShop $baseUserShop, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$baseUserShop->getId(), $request->request->get('_token'))) {
            $this->em->remove($baseUserShop);
            $this->em->flush();
        }
    }
}
