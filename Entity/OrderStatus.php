<?php

namespace Akyos\ShopBundle\Entity;

use Akyos\ShopBundle\Repository\OrderStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderStatusRepository::class)
 */
class OrderStatus
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
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="orderStatus")
     */
    private $orderOfStatus;

    /**
     * @ORM\OneToOne(targetEntity=OrderMail::class, inversedBy="orderStatus", cascade={"persist", "remove"})
     */
    private $orderEmail;

    public function __construct()
    {
        $this->orderOfStatus = new ArrayCollection();
    }

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

    /**
     * @return Collection|Order[]
     */
    public function getOrderOfStatus(): Collection
    {
        return $this->orderOfStatus;
    }

    public function addOrderOfStatus(Order $orderOfStatus): self
    {
        if (!$this->orderOfStatus->contains($orderOfStatus)) {
            $this->orderOfStatus[] = $orderOfStatus;
            $orderOfStatus->setOrderStatus($this);
        }

        return $this;
    }

    public function removeOrderOfStatus(Order $orderOfStatus): self
    {
        if ($this->orderOfStatus->contains($orderOfStatus)) {
            $this->orderOfStatus->removeElement($orderOfStatus);
            // set the owning side to null (unless already changed)
            if ($orderOfStatus->getOrderStatus() === $this) {
                $orderOfStatus->setOrderStatus(null);
            }
        }

        return $this;
    }

    public function getOrderEmail(): ?OrderMail
    {
        return $this->orderEmail;
    }

    public function setOrderEmail(?OrderMail $orderEmail): self
    {
        $this->orderEmail = $orderEmail;

        return $this;
    }
}
