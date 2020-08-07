<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\OrderMail;
use Akyos\ShopBundle\Entity\OrderStatus;
use Akyos\ShopBundle\Form\Handler\OrderMailHandler;
use Akyos\ShopBundle\Form\Type\OrderMail\OrderMailType;
use Akyos\ShopBundle\Repository\OrderMailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("admin/commandes-mails", name="orderMail_")
 */
class OrderMailController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param OrderMailRepository $orderMailRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(OrderMailRepository $orderMailRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $orderMailRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Mails de commandes',
            'entity' => 'OrderMail',
            'route' => 'orderMail',
            'fields' => [
                'Id' => 'Id',
                'Titre' => 'Title',
                'Couleur' => 'Color',
                'Position' => 'Position',
            ],
        ]);
    }

    /**
     * @Route("/new/{id}", name="new", methods={"GET","POST"})
     *
     * @param OrderStatus $orderStatus
     * @param Request $request
     * @param OrderMailHandler $orderMailHandler
     *
     * @return Response
     */
    public function new(OrderStatus $orderStatus, Request $request, OrderMailHandler $orderMailHandler)
    {
        $orderMail = new OrderMail();
        $orderMail->setOrderStatus($orderStatus);
        $orderMailForm = $this->createForm(OrderMailType::class, $orderMail);
        if ($orderMailHandler->new($orderMailForm, $request)) {
            return $this->redirectToRoute('orderMail_edit', ['id' => $orderMail->getId()]);
        }
        return $this->render('@AkyosCore/crud/new.html.twig', [
            'title' => "Nouveau statut de commande",
            'entity' => 'OrderMail',
            'route' => 'orderMail',
            'form' => $orderMailForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param OrderMail $orderMail
     * @param OrderMailHandler $orderMailHandler
     * @return Response
     */
    public function edit(Request $request, OrderMail $orderMail, OrderMailHandler $orderMailHandler): Response
    {
        $form = $this->createForm(OrderMailType::class, $orderMail);

        if($orderMailHandler->edit($form, $request)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $orderMail,
            'title' => 'un status de commande',
            'entity' => 'OrderMail',
            'route' => 'orderMail',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param OrderMail $orderMail
     * @param OrderMailHandler $orderMailHandler
     * @return Response
     */
    public function delete(Request $request, OrderMail $orderMail, OrderMailHandler $orderMailHandler): Response
    {
        $orderMailHandler->delete($orderMail, $request);
        return $this->redirectToRoute('orderMail_index');
    }
}