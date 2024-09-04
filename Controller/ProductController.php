<?php

namespace Akyos\ShopBundle\Controller;

use App\Entity\Shop\Product;
use Akyos\ShopBundle\Form\Handler\ProductHandler;
use Akyos\ShopBundle\Form\Type\Product\ProductType;
use App\Repository\Shop\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route(path: '/admin/produits', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $productRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Products',
            'entity' => 'Product',
            'route' => 'product',
            'fields' => [
                'Id' => 'Id',
                'Nom du produit' => 'Name',
            ]
        ]);
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductHandler $productHandler) : Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        if ($productHandler->new($form, $request)) {
            return $this->redirectToRoute('product_index');
        }

        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau Product",
            'entity' => 'Product',
            'route' => 'product'
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductHandler $productHandler): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        if($productHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $product,
            'title' => 'Product',
            'entity' => 'Product',
            'route' => 'product',
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Product $product, ProductHandler $productHandler): Response
    {
        $productHandler->delete($product, $request);
        return $this->redirectToRoute('product_index');
    }
}