<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, mappedBy="payment", cascade={"persist", "remove"})
     */
    private $OrderOfPayment;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderOfPayment(): ?Order
    {
        return $this->OrderOfPayment;
    }

    public function setOrderOfPayment(Order $OrderOfPayment): self
    {
        $this->OrderOfPayment = $OrderOfPayment;

        // set the owning side of the relation if necessary
        if ($OrderOfPayment->getPayment() !== $this) {
            $OrderOfPayment->setPayment($this);
        }

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }
}
