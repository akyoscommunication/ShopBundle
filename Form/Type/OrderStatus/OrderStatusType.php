<?php

namespace Akyos\ShopBundle\Form\Type\OrderStatus;

use Akyos\ShopBundle\Entity\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre du statut",
            ])
            ->add('color', ColorType::class, [
                'label' => "Couleur du statut",
            ])
            ->add('position', IntegerType::class, [
                'label' => "Position du statut",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderStatus::class,
            "translation_domain" => "forms",
        ]);
    }
}