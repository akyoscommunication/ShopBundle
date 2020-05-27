<?php

namespace Akyos\ShopBundle\Form\Order;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Order;
use Akyos\ShopBundle\Form\Cart\CartOrderType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderTypeNew extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Order $order */
        $order = $builder->getData();

        $builder
            ->add('orderStatus', null, [
                'label' => "Statut de la commande"
            ])
            ->add('shippingMode', null, [
                'label' => "Mode de livraison"
            ])
            ->add('invoiceAddress', null, [
                'label' => "Adresse de facturation",
                'choices' => $order->getClient()->getAddresses(),
                'multiple' => false,
                'expanded' => false,
                'block_prefix' => 'invoice_address',
                'choice_label' => function ($choice) {
                    return $choice->getTitle();
                },
            ])
            ->add('deliveryAddress', null, [
                'label' => "Adresse de livraison",
                'choices' => $order->getClient()->getAddresses(),
                'multiple' => false,
                'expanded' => false,
                'block_prefix' => 'delivery_address',
                'choice_label' => function ($choice) {
                    return $choice->getTitle();
                },
            ])
            ->add('payment', null, [
                'label' => "Paiement",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            "translation_domain" => "forms",
        ]);
    }
}
