<?php

namespace Akyos\ShopBundle\Form;

use Akyos\ShopBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref')
            ->add('orderStatus')
            ->add('shippingMode')
            ->add('client')
            ->add('address')
            ->add('payment')
            ->add('cartItems')
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
