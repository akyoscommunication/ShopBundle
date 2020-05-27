<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\ShopAddress;
use Akyos\ShopBundle\Form\Handler\ShopAddressHandler;
use Akyos\ShopBundle\Form\Address\ShopAddressType;
use Akyos\ShopBundle\Repository\ShopAddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/shopAddress", name="shopAddress_")
 */
class ShopAddressController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ShopAddressRepository $shopAddressRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ShopAddressRepository $shopAddressRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $shopAddressRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'ShopAddresss',
            'entity' => 'ShopAddress',
            'route' => 'shopAddress',
            'fields' => [
                'Id' => 'Id',
                "Prénom" => 'FirstName',
                "Nom" => 'LastName',
                "Adresse" => 'Address',
                "Code postal" => 'Zip',
                "Pays" => 'City',
            ]
        ]);
    }

    /**
     * @Route("/new/{client}", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param ShopAddressHandler $shopAddressHandler
     * @return Response
     */
    public function new(BaseUserShop $client, Request $request, ShopAddressHandler $shopAddressHandler) : Response
    {
        $shopAddress = new ShopAddress();
        $shopAddress->setClient($client);

        $form = $this->createForm(ShopAddressType::class, $shopAddress);
        if ($shopAddressHandler->new($form, $request)) {
            return $this->redirectToRoute('shopAddress_index');
        }
        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau ShopAddress",
            'entity' => 'ShopAddress',
            'route' => 'shopAddress'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param ShopAddress $shopAddress
     * @return Response
     */
    public function show(ShopAddress $shopAddress): Response
    {
        return $this->render('shopAddress/show.html.twig', [
            'shopAddress' => $shopAddress,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param ShopAddress $shopAddress
     * @param ShopAddressHandler $shopAddressHandler
     * @return Response
     */
    public function edit(Request $request, ShopAddress $shopAddress, ShopAddressHandler $shopAddressHandler): Response
    {
        $form = $this->createForm(ShopAddressType::class, $shopAddress);

        if($shopAddressHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $shopAddress,
            'title' => 'ShopAddress',
            'entity' => 'ShopAddress',
            'route' => 'shopAddress',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param ShopAddress $shopAddress
     * @param ShopAddressHandler $shopAddressHandler
     * @return Response
     */
    public function delete(Request $request, ShopAddress $shopAddress, ShopAddressHandler $shopAddressHandler): Response
    {
        $shopAddressHandler->delete($shopAddress, $request);
        return $this->redirectToRoute('shopAddress_index');
    }
}