<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\OrderMailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderMailRepository::class)
 */
class OrderMail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToOne(targetEntity=OrderStatus::class, mappedBy="orderEmail", cascade={"persist", "remove"})
     */
    private $orderStatus;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        // set (or unset) the owning side of the relation if necessary
        $newOrderEmail = null === $orderStatus ? null : $this;
        if ($orderStatus->getOrderEmail() !== $newOrderEmail) {
            $orderStatus->setOrderEmail($newOrderEmail);
        }

        return $this;
    }
}
