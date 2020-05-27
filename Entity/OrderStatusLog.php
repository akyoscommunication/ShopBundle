<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\OrderStatusLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=OrderStatusLogRepository::class)
 */
class OrderStatusLog
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderStatusLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderOfStatusLog;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderOfStatusLog(): ?Order
    {
        return $this->orderOfStatusLog;
    }

    public function setOrderOfStatusLog(?Order $orderOfStatusLog): self
    {
        $this->orderOfStatusLog = $orderOfStatusLog;

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }
}
