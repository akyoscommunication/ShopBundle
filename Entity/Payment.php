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
     * @ORM\OneToMany(targetEntity=PaymentType::class, mappedBy="payment")
     */
    private $paymentType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    public function __construct()
    {
        $this->paymentType = new ArrayCollection();
    }

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

    /**
     * @return Collection|PaymentType[]
     */
    public function getPaymentType(): Collection
    {
        return $this->paymentType;
    }

    public function addPaymentType(PaymentType $paymentType): self
    {
        if (!$this->paymentType->contains($paymentType)) {
            $this->paymentType[] = $paymentType;
            $paymentType->setPayment($this);
        }

        return $this;
    }

    public function removePaymentType(PaymentType $paymentType): self
    {
        if ($this->paymentType->contains($paymentType)) {
            $this->paymentType->removeElement($paymentType);
            // set the owning side to null (unless already changed)
            if ($paymentType->getPayment() === $this) {
                $paymentType->setPayment(null);
            }
        }

        return $this;
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

    public function __toString()
    {
        return $this->title;
    }
}
