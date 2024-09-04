<?php

namespace Akyos\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
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

#[Route(path: 'admin/commandes-mails', name: 'orderMail_')]
class OrderMailController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
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

    
    #[Route(path: '/new/{id}', name: 'new', methods: ['GET', 'POST'])]
    public function new(OrderStatus $orderStatus, Request $request, OrderMailHandler $orderMailHandler): RedirectResponse|Response
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
            'route' => 'orderStatus',
            'form' => $orderMailForm->createView()
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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
            'route' => 'orderStatus',
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, OrderMail $orderMail, OrderMailHandler $orderMailHandler): Response
    {
        $orderMailHandler->delete($orderMail, $request);
        return $this->redirectToRoute('orderMail_index');
    }
}