<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Form\Handler\BaseUserShopHandler;
use Akyos\ShopBundle\Form\Type\BaseUserShop\BaseUserShopType;
use Akyos\ShopBundle\Repository\BaseUserShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin/client", name="baseUserShop_")
 */
class BaseUserShopController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param BaseUserShopRepository $baseUserShopRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(BaseUserShopRepository $baseUserShopRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $baseUserShopRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosShop/client/index.html.twig', [
            'els' => $els,
            'title' => 'BaseUserShops',
            'entity' => 'BaseUserShop',
            'route' => 'baseUserShop',
            'fields' => [
                'Id' => 'Id',
                'Email' => 'Email',
            ]
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param BaseUserShopHandler $baseUserShopHandler
     * @return Response
     */
    public function new(Request $request, BaseUserShopHandler $baseUserShopHandler) : Response
    {
        $baseUserShop = new BaseUserShop();
        $form = $this->createForm(BaseUserShopType::class, $baseUserShop);
        if ($baseUserShopHandler->new($form, $request)) {
            return $this->redirectToRoute('baseUserShop_index');
        }
        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau BaseUserShop",
            'entity' => 'BaseUserShop',
            'route' => 'baseUserShop'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param BaseUserShop $baseUserShop
     * @return Response
     */
    public function show(BaseUserShop $baseUserShop): Response
    {
        return $this->render('@AkyosShop/client/show.html.twig', [
            'route' => 'baseUserShop',
            'baseUserShop' => $baseUserShop,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param BaseUserShop $baseUserShop
     * @param BaseUserShopHandler $baseUserShopHandler
     * @return Response
     */
    public function edit(Request $request, BaseUserShop $baseUserShop, BaseUserShopHandler $baseUserShopHandler): Response
    {
        $form = $this->createForm(BaseUserShopType::class, $baseUserShop);

        if($baseUserShopHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $baseUserShop,
            'title' => 'BaseUserShop',
            'entity' => 'BaseUserShop',
            'route' => 'baseUserShop',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param BaseUserShop $baseUserShop
     * @param BaseUserShopHandler $baseUserShopHandler
     * @return Response
     */
    public function delete(Request $request, BaseUserShop $baseUserShop, BaseUserShopHandler $baseUserShopHandler): Response
    {
        $baseUserShopHandler->delete($baseUserShop, $request);
        return $this->redirectToRoute('baseUserShop_index');
    }
}