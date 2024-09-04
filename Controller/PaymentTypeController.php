<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\PaymentType;
use Akyos\ShopBundle\Form\Handler\PaymentTypeHandler;
use Akyos\ShopBundle\Form\Type\Payment\PaymentTypeType;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route(path: '/admin/paymentType', name: 'paymentType_')]
class PaymentTypeController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(PaymentTypeRepository $paymentTypeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $paymentTypeRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'PaymentTypes',
            'entity' => 'PaymentType',
            'route' => 'paymentType',
            'fields' => [
                'Id' => 'Id',
                'Titre du moyen de paiement' => 'Title',
            ]
        ]);
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentTypeHandler $paymentTypeHandler) : Response
    {
        $paymentType = new PaymentType();
        $form = $this->createForm(PaymentTypeType::class, $paymentType);
        if ($paymentTypeHandler->new($form, $request)) {
            return $this->redirectToRoute('paymentType_index');
        }

        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau PaymentType",
            'entity' => 'PaymentType',
            'route' => 'paymentType'
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(PaymentType $paymentType): Response
    {
        return $this->render('paymentType/show.html.twig', [
            'paymentType' => $paymentType,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentType $paymentType, PaymentTypeHandler $paymentTypeHandler): Response
    {
        $form = $this->createForm(PaymentTypeType::class, $paymentType);

        if($paymentTypeHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $paymentType,
            'title' => 'PaymentType',
            'entity' => 'PaymentType',
            'route' => 'paymentType',
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['DELETE', 'POST'])]
    public function delete(Request $request, PaymentType $paymentType, PaymentTypeHandler $paymentTypeHandler): Response
    {
        $paymentTypeHandler->delete($paymentType, $request);
        return $this->redirectToRoute('paymentType_index');
    }
}
