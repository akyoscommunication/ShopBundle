<?php

namespace Akyos\ShopBundle\Form\Type\Payment;

use Akyos\FileManagerBundle\Form\Type\FileManagerType;
use Akyos\ShopBundle\Entity\PaymentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentType::class,
            "translation_domain" => "forms",
        ]);
    }
}
