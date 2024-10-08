<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\OrderStatus;
use Akyos\ShopBundle\Form\Handler\OrderStatusHandler;
use Akyos\ShopBundle\Form\Type\OrderStatus\OrderStatusType;
use Akyos\ShopBundle\Repository\OrderStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("admin/commandes-statuts", name="orderStatus_")
 */
class OrderStatusController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param OrderStatusRepository $orderStatusRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(OrderStatusRepository $orderStatusRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $orderStatusRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Statuts de commandes',
            'entity' => 'OrderStatus',
            'route' => 'orderStatus',
            'fields' => [
                'Id' => 'Id',
                'Titre' => 'Title',
                'Couleur' => 'Color',
                'Position' => 'Position',
            ],
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     *
     * @param Request $request
     * @param OrderStatusHandler $orderStatusHandler
     *
     * @return Response
     */
    public function new(Request $request, OrderStatusHandler $orderStatusHandler)
    {
        $orderStatus = new OrderStatus();
        $orderStatusForm = $this->createForm(OrderStatusType::class, $orderStatus);
        if ($orderStatusHandler->new($orderStatusForm, $request)) {
            return $this->redirectToRoute('orderStatus_edit', ['id' => $orderStatus->getId()]);
        }
        return $this->render('@AkyosShop/orderStatus/new.html.twig', [
            'title' => "Nouveau statut de commande",
            'entity' => 'OrderStatus',
            'route' => 'orderStatus',
            'form' => $orderStatusForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param OrderStatus $orderStatus
     * @param OrderStatusHandler $orderStatusHandler
     * @return Response
     */
    public function edit(Request $request, OrderStatus $orderStatus, OrderStatusHandler $orderStatusHandler): Response
    {
        $form = $this->createForm(OrderStatusType::class, $orderStatus);

        if($orderStatusHandler->edit($form, $request)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosShop/orderStatus/edit.html.twig', [
            'el' => $orderStatus,
            'title' => 'un status de commande',
            'entity' => 'OrderStatus',
            'route' => 'orderStatus',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param OrderStatus $orderStatus
     * @param OrderStatusHandler $orderStatusHandler
     * @return Response
     */
    public function delete(Request $request, OrderStatus $orderStatus, OrderStatusHandler $orderStatusHandler): Response
    {
        $orderStatusHandler->delete($orderStatus, $request);
        return $this->redirectToRoute('orderStatus_index');
    }
}