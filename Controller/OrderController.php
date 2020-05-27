<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\Order;
use Akyos\ShopBundle\Entity\OrderStatus;
use Akyos\ShopBundle\Entity\OrderStatusLog;
use Akyos\ShopBundle\Entity\ShopAddress;
use Akyos\ShopBundle\Form\Address\ShopAddressType;
use Akyos\ShopBundle\Form\Cart\CartType;
use Akyos\ShopBundle\Form\Handler\OrderHandler;
use Akyos\ShopBundle\Form\Handler\ShopAddressHandler;
use Akyos\ShopBundle\Form\Order\OrderTypeNew;
use Akyos\ShopBundle\Repository\OrderRepository;
use Akyos\ShopBundle\Service\Mailer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param OrderRepository $orderRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $orderRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Orders',
            'entity' => 'Order',
            'route' => 'order',
            'fields' => [
                'Id' => 'Id',
                'Référence' => 'Ref',
            ],
            'button_add' => false
        ]);
    }

    /**
     * @Route("/new/{cart}", name="new", methods={"GET","POST"})
     *
     * @param Cart $cart
     * @param Request $request
     * @param OrderHandler $orderHandler
     *
     * @param ShopAddressHandler $shopAddressHandler
     * @return Response
     */
    public function new(Cart $cart, Request $request, OrderHandler $orderHandler, ShopAddressHandler $shopAddressHandler) : Response
    {
        if ($cart->getOrderOfCart()) {
            return $this->redirectToRoute('baseUserShop_show', ['id' => $cart->getClient()->getId()]);
        }

        $order = new Order();
        $order->setCart($cart);
        $order->setClient($cart->getClient());
        $order->setRef(substr(uniqid('', true), -5));

        $invoiceAddress = new ShopAddress();
        $invoiceAddress->setClient($cart->getClient());

        $formAddress = $this->createForm(ShopAddressType::class, $invoiceAddress);
        if ($shopAddressHandler->new($formAddress, $request)) {
            return $this->redirect($request->getUri());
        }

        $form = $this->createForm(OrderTypeNew::class, $order);
        if ($orderHandler->new($form, $request)) {
            return $this->redirectToRoute('baseUserShop_show', ['id' => $cart->getClient()->getId()]);
        }
        return $this->render('@AkyosShop/order/new.html.twig', [
            'form' => $form->createView(),
            'formAddress' => $formAddress->createView(),
            'title' => "Nouveau Order",
            'entity' => 'Order',
            'route' => 'order'
        ]);
    }

    /**
     * @Route("/{statusLog}", name="resendmail_status", methods={"GET"})
     * @param OrderStatusLog $statusLog
     * @param Mailer $mailer
     *
     * @return bool
     */
    public function resendMailStatus(OrderStatusLog $statusLog, Mailer $mailer)
    {
        $order = $statusLog->getOrderOfStatusLog();
        $orderStatusMail = $statusLog->getOrderStatus()->getOrderEmail();

        return $mailer->sendMessage($order->getClient()->getEmail(), $orderStatusMail->getSubject(), $orderStatusMail->getTemplate(), $order);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param Order $order
     * @return Response
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Order $order
     * @param OrderHandler $orderHandler
     * @param ShopAddressHandler $shopAddressHandler
     * @return Response
     */
    public function edit(Request $request, Order $order, OrderHandler $orderHandler, ShopAddressHandler $shopAddressHandler): Response
    {
        $invoiceAddress = new ShopAddress();
        $invoiceAddress->setClient($order->getClient());

        $formAddress = $this->createForm(ShopAddressType::class, $invoiceAddress);
        if ($shopAddressHandler->new($formAddress, $request)) {
            return $this->redirect($request->getUri());
        }

        $form = $this->createForm(OrderTypeNew::class, $order);

        if($orderHandler->edit($form, $request)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosShop/order/new.html.twig', [
            'el' => $order,
            'title' => 'Order',
            'entity' => 'Order',
            'route' => 'order',
            'formAddress' => $formAddress->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Order $order
     * @param OrderHandler $orderHandler
     * @return Response
     */
    public function delete(Request $request, Order $order, OrderHandler $orderHandler): Response
    {
        $orderHandler->delete($order, $request);
        return $this->redirectToRoute('order_index');
    }
}