<?php

namespace Akyos\ShopBundle\DataFixtures;

use Akyos\ShopBundle\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $payments = [
            'Chèque',
            'Virement'
        ];

        foreach ($payments as $p) {
            $payment = new Payment();
            $payment->setTitle($p);
            $manager->persist($payment);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['shop', 'order-status'];
    }
}
