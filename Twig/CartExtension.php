<?php

namespace Akyos\ShopBundle\Twig;

use Akyos\ShopBundle\Entity\Cart;
use Akyos\ShopBundle\Entity\CartItem;
use Akyos\ShopBundle\Service\Cart\CartService;
use NumberFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    /** @var CartService */
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTotalPriceOfCartWtPrice', [$this, 'getTotalPriceOfCartWtPrice']),
            new TwigFunction('getTotalOfCartItems', [$this, 'getTotalOfCartItems']),
            new TwigFunction('getTotalPriceOfCart', [$this, 'getTotalPriceOfCart']),
            new TwigFunction('getDevise', [$this, 'getDevise']),
        ];
    }

    public function getTotalPriceOfCartWtPrice(Cart $cart)
    {
        $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );
        $total = $this->cartService->getTotal($cart);

        return numfmt_format_currency($fmt, $total, 'EUR');
    }

    public function getTotalPriceOfCart(Cart $cart, $wtPrice = true)
    {
        $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );
        $total = $this->cartService->getTotal($cart);

        return $wtPrice ? numfmt_format_currency($fmt, $total, 'EUR') : $total;
    }

    public function getTotalOfCartItems(array $cartItems, array $entities = [], $wtPrice = true)
    {
        $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );

        if (!empty($entities)) {
            $cartItems = array_filter($cartItems, function(CartItem $cartItem) use ($entities) {
                foreach ($entities as $e) {
                    if ($cartItem->getProduct() instanceof $e) {
                        return true;
                    }
                }
                return false;
            });
        }

        $total = 0;

        foreach ($cartItems as $cartItem) {
            $total += $cartItem->getPrice() * $cartItem->getQty();
        }

        return $wtPrice ? numfmt_format_currency($fmt, $total, 'EUR') : $total;
    }

    public function getDevise()
    {
        return '€';
    }
}
