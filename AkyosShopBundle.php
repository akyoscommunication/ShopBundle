<?php

namespace Akyos\ShopBundle;

use Akyos\ShopBundle\DependencyInjection\ShopBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AkyosShopBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ShopBundleExtension();
    }
}