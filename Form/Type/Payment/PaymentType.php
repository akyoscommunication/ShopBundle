<?php

namespace Akyos\ShopBundle\Form\Type\Payment;

use Akyos\ShopBundle\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isPaid', CheckboxType::class, [
                'label' => "La commande est-elle payée ?",
                'required' => false
            ])
            ->add('content', TextareaType::class, [
                'label' => "Détail du paiement"
            ])
            ->add('paymentType', EntityType::class, [
                'class' => \Akyos\ShopBundle\Entity\PaymentType::class,
                'label' => "Type de paiement"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            "translation_domain" => "forms",
        ]);
    }
}
