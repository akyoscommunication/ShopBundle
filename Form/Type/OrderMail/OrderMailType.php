<?php

namespace Akyos\ShopBundle\Form\Type\OrderMail;

use Akyos\ShopBundle\Entity\OrderMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('subject', TextType::class, [
                'label' => 'Objet du mail'
            ])
            ->add('template', TextType::class, [
                'label' => 'Template',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderMail::class,
        ]);
    }
}
