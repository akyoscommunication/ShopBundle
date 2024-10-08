<?php

namespace Akyos\ShopBundle\Form\Type\Cart;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cartItems', CartOrderType::class, [
                'label' => "Panier",
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
            "translation_domain" => "forms",
        ]);
    }
}
