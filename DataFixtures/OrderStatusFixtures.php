<?php

namespace Akyos\ShopBundle\DataFixtures;

use Akyos\ShopBundle\Entity\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $orderStatus = [
            [
                'title' => 'En attente de paiement',
                'color' => '#ffeaa7',
                'email' => '',
            ],
            [
                'title' => 'En cours de traîtement',
                'color' => '#55efc4',
                'email' => '',
            ],
            [
                'title' => 'Annulé',
                'color' => '#ff7675',
                'email' => '',
            ],
            [
                'title' => 'Payé',
                'color' => '#74b9ff',
                'email' => '',
            ],
            [
                'title' => 'Livrée',
                'color' => '#00b894',
                'email' => '',
            ],
            [
                'title' => 'Gelé',
                'color' => '#81ecec',
                'email' => '',
            ],
            [
                'title' => 'Terminée',
                'color' => '#00cec9',
                'email' => '',
            ],
        ];

        foreach ($orderStatus as $k => $s) {
            $status = new OrderStatus();
            $status->setTitle($s['title']);
            $status->setColor($s['color']);
            $manager->persist($status);
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
