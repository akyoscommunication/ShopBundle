<?php

namespace Akyos\ShopBundle\Form\Handler;

use App\Entity\Shop\Order;
use Akyos\ShopBundle\Entity\Payment;
use Akyos\ShopBundle\Service\Payment\PaypalApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class OrderHandler extends AbstractController
{
    private $em;
    private $paypalApiService;

    public function __construct(EntityManagerInterface $entityManager, PaypalApiService $paypalApiService)
    {
        $this->em = $entityManager;
        $this->paypalApiService = $paypalApiService;
    }

    public function new(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            $paymentType = $form->get('paymentType')->getData();

            switch ($paymentType->getTitle()) {
                case 'Paypal':
                    $this->paypalApiService->createPayement($order);
                    break;
                default:
                    break;
            }

            $payment = new Payment();
            $payment->setPaymentType($paymentType);
            $payment->setOrderOfPayment($order);

            $this->em->persist($payment);
            $this->em->persist($order);
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

    public function delete(Order $order, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $order->getId(), $request->request->get('_token'))) {
            $this->em->remove($order);
            $this->em->flush();
        }
    }
}
