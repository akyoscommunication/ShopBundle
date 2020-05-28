<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\PaymentType;
use Akyos\ShopBundle\Form\Handler\PaymentTypeHandler;
use Akyos\ShopBundle\Form\PaymentTypeType;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin/paymentType", name="paymentType_")
 */
class PaymentTypeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param PaymentTypeRepository $paymentTypeRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
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

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param PaymentTypeHandler $paymentTypeHandler
     * @return Response
     */
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

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param PaymentType $paymentType
     * @return Response
     */
    public function show(PaymentType $paymentType): Response
    {
        return $this->render('paymentType/show.html.twig', [
            'paymentType' => $paymentType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param PaymentType $paymentType
     * @param PaymentTypeHandler $paymentTypeHandler
     * @return Response
     */
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

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param PaymentType $paymentType
     * @param PaymentTypeHandler $paymentTypeHandler
     * @return Response
     */
    public function delete(Request $request, PaymentType $paymentType, PaymentTypeHandler $paymentTypeHandler): Response
    {
        $paymentTypeHandler->delete($paymentType, $request);
        return $this->redirectToRoute('paymentType_index');
    }
}