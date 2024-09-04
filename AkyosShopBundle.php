<?php

namespace Akyos\ShopBundle;

use Akyos\ShopBundle\DependencyInjection\ShopBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class AkyosShopBundle extends Bundle
{
	public function getContainerExtension(): ?ExtensionInterface
	{
		return new ShopBundleExtension();
	}
}
