<?php

namespace Akyos\ShopBundle\Form\Address;

use Akyos\ShopBundle\Entity\ShopAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', null, [
                'placeholder' => 'Choisir un client',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('title')
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('vatNumber')
            ->add('address')
            ->add('addressTwo')
            ->add('zip')
            ->add('city')
            ->add('country')
            ->add('homephone')
            ->add('cellphone')
            ->add('other')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShopAddress::class,
        ]);
    }
}
