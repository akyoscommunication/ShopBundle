<?php

namespace Akyos\ShopBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExtendSidebar
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getTemplate($route)
    {
        $template = '
        <li class="">
            <a href="#shop" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">Shop</a>
            <ul class="list-unstyled collapse" id="shop" style="">
                <li class="'.(strpos($route,"product") !== false ? "active" : "").'">
                    <a href="'.$this->router->generate('product_index').'">Produits</a>
                    <a href="'.$this->router->generate('order_index').'">Commandes</a>
                    <a href="'.$this->router->generate('cart_index').'">Paniers</a>
                    <a href="'.$this->router->generate('shopAddress_index').'">Adresses</a>
                    <a href="'.$this->router->generate('baseUserShop_index').'">Clients</a>
                </li>
            </ul>
        </li>
        ';
        return new Response($template);
    }

    public function getOptionsTemplate($route)
    {
        $template = '<li class="'.(strpos($route,"shop_options") !== false ? "active" : "").'"><a href="'.$this->router->generate('shop_options').'">ShopBundle</a></li>';
        return new Response($template);
    }
}