<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Form\Type\Order\OrderExportType;
use Akyos\ShopBundle\Service\Cart\CartService;
use App\Entity\Shop\Order;
use Akyos\ShopBundle\Entity\OrderStatusLog;
use Akyos\ShopBundle\Entity\ShopAddress;
use Akyos\ShopBundle\Form\Type\Address\ShopAddressType;
use Akyos\ShopBundle\Form\Handler\OrderHandler;
use Akyos\ShopBundle\Form\Handler\ShopAddressHandler;
use Akyos\ShopBundle\Form\Type\Order\OrderTypeNew;
use App\Repository\Shop\OrderRepository;
use Akyos\ShopBundle\Service\Mailer;
use Akyos\ShopBundle\Service\Payment\PaypalApiService;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin/commande", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     * @param OrderRepository $orderRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CartService $cartService
     *
     * @return Response
     */
    public function index(OrderRepository $orderRepository, PaginatorInterface $paginator, Request $request, CartService $cartService): Response
    {
        $els = $paginator->paginate(
            $orderRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        $exportForm = $this->createForm(OrderExportType::class);
        $exportForm->handleRequest($request);
        if ($exportForm->isSubmitted() && $exportForm->isValid()) {
            $format = 'Y-m-d';
            /** @var \DateTime $beginAt */
            /** @var \DateTime $endAt */
            $beginAt = $exportForm->get('beginAt')->getData();
            $endAt = $exportForm->get('endAt')->getData();
            $orders = $orderRepository->findByTimeRange($beginAt, $endAt);
            $filename = 'orders-'.$beginAt->format($format).'-'.$endAt->format($format).'.csv';
            $csv = Writer::createFromString('');

            $records = [
                [
                    'Date de création',
                    'Client',
                    'Référence de la commande',
                    'HT',
                    'TTC'
                ]
            ];

            foreach ($orders as $order) {
                $total = $cartService->getTotal($order->getCart());

                $records[] = [
                    $order->getCreatedAt()->format($format),
                    $order->getClient() ? $order->getClient()->getEmail() : $order->getCart()->getToken(),
                    $order->getRef(),
                    ($total/1.2),
                    $total,
                ];
            }

            $csv->insertAll($records);

            $response = new Response($csv->getContent());
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);

            return $response;
        }

        return $this->render('@AkyosShop/order/index.html.twig', [
            'els' => $els,
            'title' => "Commandes",
            'entity' => Order::class,
            'route' => 'order',
            'fields' => [
                'Id' => 'Id',
                'Référence' => 'Ref',
                'Création' => 'CreatedAt',
            ],
            'button_add' => false,
            'exportForm' => $exportForm->createView(),
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
     * @Route("/resend-mail/status/{statusLog}", name="resendmail_status", methods={"GET"})
     * @param OrderStatusLog $statusLog
     * @param Mailer $mailer
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function resendMailStatus(OrderStatusLog $statusLog, Mailer $mailer, Request $request)
    {
        $order = $statusLog->getOrderOfStatusLog();
        $orderStatusMail = $statusLog->getOrderStatus()->getOrderEmail();
        if ($mailer->sendMessage($order->getClient()->getEmail(), $orderStatusMail->getSubject(), $orderStatusMail->getTemplate(), $order)) {
            $this->addFlash('success', "L'email à bien été renvoyé.");
            return $this->redirect($request->get('callback'));
        }
    }

    /**
     * @Route("/detail/{id}", name="show", methods={"GET"})
     * @param Order $order
     * @return Response
     */
    public function show(Order $order): Response
    {
        return $this->render('@AkyosShop/order/show.html.twig', [
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

    /**
     * @Route("/capture/payement", name="capture", methods={"GET"})
     * @param Request $request
     * @param PaypalApiService $paypalApiService
     * @return Response
     */
    public function capture(Request $request, PaypalApiService $paypalApiService) : Response
    {
        $body = 'TEST';
        $order = $paypalApiService->capturePayment($request);
        return $this->render('@AkyosShop/email/default.html.twig', [
            'el' => $order,
            'body' => $body
        ]);
    }
}