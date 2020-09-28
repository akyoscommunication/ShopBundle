<?php

namespace Akyos\ShopBundle\Twig;

use Akyos\ShopBundle\Entity\Cart;
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
            new TwigFunction('getTotalPriceOfCart', [$this, 'getTotalPriceOfCart']),
            new TwigFunction('getDevise', [$this, 'getDevise']),
        ];
    }

    public function getTotalPriceOfCart(Cart $cart)
    {
        $fmt = numfmt_create( 'fr_FR', NumberFormatter::CURRENCY );

        return numfmt_format_currency($fmt, $this->cartService->getTotal($cart), 'EUR');
    }

    public function getDevise()
    {
        return '€';
    }
}
