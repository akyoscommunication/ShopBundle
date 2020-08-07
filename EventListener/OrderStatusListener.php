<?php

namespace Akyos\ShopBundle\EventListener;

use App\Entity\Shop\Order;
use Akyos\ShopBundle\Entity\OrderStatus;
use Akyos\ShopBundle\Entity\OrderStatusLog;
use Akyos\ShopBundle\Service\Mailer;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OrderStatusListener
{
    /** @var Mailer */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postUpdate(Order $order, LifecycleEventArgs $event)
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
        /** @var array $changeSet */
        $changeSet = $uow->getEntityChangeSet($order);

        if (isset($changeSet['orderStatus'])) {
            /** @var OrderStatus $newOrderStatus */
            $newOrderStatus = $changeSet['orderStatus'][1];

            $orderStatusLog = new OrderStatusLog();
            $orderStatusLog->setOrderStatus($newOrderStatus);
            $orderStatusLog->setOrderOfStatusLog($order);

            if ($newOrderStatus->getOrderEmail()) {
                $this->mailer->sendMessage($order->getClient()->getEmail(), $newOrderStatus->getOrderEmail()->getSubject(), $newOrderStatus->getOrderEmail()->getTemplate(), $order);
            }
            $em->persist($orderStatusLog);
            $em->flush();
        }

        return true;
    }
}