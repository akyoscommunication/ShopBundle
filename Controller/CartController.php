<?php

namespace Akyos\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\CartItem;
use App\Entity\Shop\Product;
use Akyos\ShopBundle\Form\Type\Cart\CartItemAddType;
use Akyos\ShopBundle\Form\Type\Cart\CartItemType;
use Akyos\ShopBundle\Form\Handler\CartHandler;
use Akyos\ShopBundle\Form\Handler\CartItemHandler;
use Akyos\ShopBundle\Repository\CartRepository;
use Akyos\ShopBundle\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route(path: '/admin/panier', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
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

    #[Route(path: '/new/{client}', name: 'new', methods: ['GET', 'POST'])]
    public function new(BaseUserShop $client, CartHandler $cartHandler) : Response
    {
        $cart = new Cart();
        $cart->setClient($client);
        $cart->setIsSaved(false);

        $cartHandler->new($cart);
        return $this->redirectToRoute('cart_edit', ['id' => $cart->getId()]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @param int $qty
     */
    #[Route(path: '/add-to-cart/{id}/{qty}', name: 'add-to-cart', methods: ['GET'], options: ['expose' => true])]
    public function addToCart(Product $product, CartService $cartService, $qty = 1): Response
    {
        $cartService->add($product, $qty);

        return $this->redirectToRoute('home');
    }

    /**
     * @return Response
     */
    #[Route(path: '/remove-to-cart/{id}', name: 'remove-to-cart', methods: ['POST'], options: ['expose' => true])]
    public function removeToCart(CartItem $cartItem, CartService $cartService): RedirectResponse
    {
        $cartService->remove($cartItem);

        return $this->redirectToRoute('home');
    }

    /**
     * @return Response
     */
    #[Route(path: '/update-cart/{id}/{qty}', name: 'update-cart', methods: ['POST'], options: ['expose' => true])]
    public function updateCart(CartItem $cartItem, CartService $cartService, int $qty = 0): RedirectResponse
    {
        $cartService->update($cartItem, $qty);

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, CartHandler $cartHandler, CartItemHandler $cartItemHandler): Response
    {
        $form = $this->createForm(CartItemAddType::class);

        $formCiArray = [];
        foreach ($cart->getCartItems() as $ci) {
            $formCi = $this->get('form.factory')->createNamed('cartItem_' . $ci->getId(), CartItemType::class, $ci);
            $formCiArray[] = $formCi->createView();

            if ($cartItemHandler->edit($formCi, $request)){
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

    #[Route(path: '/{id}/delete', name: 'delete', methods: ['DELETE', 'POST'])]
    public function delete(Request $request, Cart $cart, CartHandler $cartHandler): Response
    {
        $cartHandler->delete($cart, $request);
        return $this->redirectToRoute('cart_index');
    }
}
