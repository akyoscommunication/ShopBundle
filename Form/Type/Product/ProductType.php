<?php

namespace Akyos\ShopBundle\Form\Type\Product;

use Akyos\FileManagerBundle\Form\Type\FileManagerType;
use App\Entity\Shop\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('thumbnail', FileManagerType::class)
            ->add('published')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            "translation_domain" => "forms",
        ]);
    }
}
