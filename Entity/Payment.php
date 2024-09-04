<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\PaymentRepository;
use App\Entity\Shop\Order;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: Order::class, mappedBy: 'payment', cascade: ['persist', 'remove'])]
    private ?Order $OrderOfPayment = null;

    #[ORM\ManyToOne(targetEntity: PaymentType::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentType $paymentType = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isPaid = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
