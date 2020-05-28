<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\CartItem;
use Akyos\ShopBundle\Entity\Product;
use Akyos\ShopBundle\Form\Cart\CartItemAddType;
use Akyos\ShopBundle\Form\Cart\CartItemType;
use Akyos\ShopBundle\Form\Handler\CartHandler;
use Akyos\ShopBundle\Form\Handler\CartItemHandler;
use Akyos\ShopBundle\Repository\CartRepository;
use Akyos\ShopBundle\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/admin/panier", name="cart_")
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
                'Sauvegardé ?' => 'IsSaved',
                'Client' => 'Client',
            ],
            'button_add' => false
        ]);
    }

    /**
     * @Route("/new/{client}", name="new", methods={"GET","POST"})
     * @param BaseUserShop $client
     * @param Request $request
     * @param CartHandler $cartHandler
     * @return Response
     */
    public function new(BaseUserShop $client, Request $request, CartHandler $cartHandler) : Response
    {
        $cart = new Cart();
        $cart->setClient($client);
        $cart->setIsSaved(false);

        $cartHandler->new($cart);
        return $this->redirectToRoute('cart_edit', ['id' => $cart->getId()]);
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
     * @Route("/add-to-cart/{id}/{qty}", name="add-to-cart", methods={"GET"})
     * @param Product $product
     * @param CartService $cartService
     * @param int $qty
     * @return Response
     */
    public function addToCart(Product $product, CartService $cartService, $qty = 1): Response
    {
        $cartService->add($product, $qty);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/remove-to-cart/{id}", name="remove-to-cart", methods={"POST"})
     * @param CartItem $cartItem
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function removeToCart(CartItem $cartItem, CartService $cartService, Request $request)
    {
        $cartService->remove($cartItem);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/update-cart/{id}/{qty}", name="update-cart", methods={"POST"})
     * @param CartItem $cartItem
     * @param CartService $cartService
     * @param Request $request
     * @param int $qty
     * @return Response
     */
    public function updateCart(CartItem $cartItem, CartService $cartService, Request $request, $qty = 0)
    {
        $cartService->update($cartItem, $qty);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Cart $cart
     * @param CartHandler $cartHandler
     * @param CartItemHandler $cartItemHandler
     * @return Response
     */
    public function edit(Request $request, Cart $cart, CartHandler $cartHandler, CartItemHandler $cartItemHandler): Response
    {
        $form = $this->createForm(CartItemAddType::class);

        $formCiArray = [];
        foreach ($cart->getCartItems() as $k => $ci) {
            $formCi = $this->get('form.factory')->createNamed("cartItem_{$ci->getId()}", CartItemType::class, $ci);
            $formCiArray[] = $formCi->createView();

            if ($cartItemHandler->new($formCi, $request)){
                return $this->redirect($request->getUri());
            }
        }

        if($cartHandler->edit($form, $request, $cart)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosShop/cart/edit.html.twig', [
            'el' => $cart,
            'title' => 'Cart',
            'entity' => 'Cart',
            'route' => 'cart',
            'formCiArray' => $formCiArray,
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