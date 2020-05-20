<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\Order;
use Akyos\ShopBundle\Form\Handler\OrderHandler;
use Akyos\ShopBundle\Form\OrderType;
use Akyos\ShopBundle\Repository\OrderRepository;
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
                'Référence' => 'ref',
            ]
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param OrderHandler $orderHandler
     * @return Response
     */
    public function new(Request $request, OrderHandler $orderHandler) : Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        if ($orderHandler->new($form, $request)) {
            return $this->redirectToRoute('order_index');
        }
        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau Order",
            'entity' => 'Order',
            'route' => 'order'
        ]);
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
     * @return Response
     */
    public function edit(Request $request, Order $order, OrderHandler $orderHandler): Response
    {
        $form = $this->createForm(OrderType::class, $order);

        if($orderHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $order,
            'title' => 'Order',
            'entity' => 'Order',
            'route' => 'order',
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