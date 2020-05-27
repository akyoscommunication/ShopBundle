<?php

namespace Akyos\ShopBundle\DataFixtures;

use Akyos\ShopBundle\Entity\Payment;
use Akyos\ShopBundle\Entity\PaymentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $paymentsTypes = [
            'Chèque',
            'Virement'
        ];

        foreach ($paymentsTypes as $p) {
            $paymentType = new PaymentType();
            $paymentType->setTitle($p);
            $manager->persist($paymentType);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['shop', 'order-payment-type'];
    }
}
