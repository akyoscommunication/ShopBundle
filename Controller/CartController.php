<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\Product;
use Akyos\ShopBundle\Form\Handler\CartHandler;
use Akyos\ShopBundle\Form\CartType;
use Akyos\ShopBundle\Repository\CartRepository;
use Akyos\ShopBundle\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CartRepository $cartRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(CartRepository $cartRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $cartRepository->createQueryBuilder('a')->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Carts',
            'entity' => 'Cart',
            'route' => 'cart',
            'fields' => [
                'Id' => 'Id',
                'Nom du produit' => 'Name',
            ]
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param CartHandler $cartHandler
     * @return Response
     */
    public function new(Request $request, CartHandler $cartHandler) : Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        if ($cartHandler->new($form, $request)) {
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('@AkyosCore/crud/new.html.twig', [
            'form' => $form->createView(),
            'title' => "Nouveau Cart",
            'entity' => 'Cart',
            'route' => 'cart'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param Cart $cart
     * @return Response
     */
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/add-to-cart/{id}", name="show", methods={"POST", "GET"})
     * @param Product $product
     * @param CartService $cartService
     * @param Request $request
     * @param CartRepository $cartRepository
     * @return Response
     */
    public function addToCart(Product $product, CartService $cartService, Request $request, CartRepository $cartRepository): Response
    {
        /** @var BaseUserShop $user */
        $user = $this->getUser();
        if ($cartService->add($cartRepository->findOneBy(['client' => $user->getId(), 'isSaved' => false]), $product)) {
            return $this->redirect($request->getUri());
        }
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Cart $cart
     * @param CartHandler $cartHandler
     * @return Response
     */
    public function edit(Request $request, Cart $cart, CartHandler $cartHandler): Response
    {
        $form = $this->createForm(CartType::class, $cart);

        if($cartHandler->edit($form, $request)) {
            return new JsonResponse('valid');
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $cart,
            'title' => 'Cart',
            'entity' => 'Cart',
            'route' => 'cart',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Cart $cart
     * @param CartHandler $cartHandler
     * @return Response
     */
    public function delete(Request $request, Cart $cart, CartHandler $cartHandler): Response
    {
        $cartHandler->delete($cart, $request);
        return $this->redirectToRoute('cart_index');
    }
}