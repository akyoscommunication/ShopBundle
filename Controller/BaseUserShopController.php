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


#[Route(path: '/admin/client', name: 'baseUserShop_')]
class BaseUserShopController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
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

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(BaseUserShop $baseUserShop): Response
    {
        return $this->render('@AkyosShop/client/show.html.twig', [
            'route' => 'baseUserShop',
            'baseUserShop' => $baseUserShop,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['DELETE', 'POST'])]
    public function delete(Request $request, BaseUserShop $baseUserShop, BaseUserShopHandler $baseUserShopHandler): Response
    {
        $baseUserShopHandler->delete($baseUserShop, $request);
        return $this->redirectToRoute('baseUserShop_index');
    }
}
