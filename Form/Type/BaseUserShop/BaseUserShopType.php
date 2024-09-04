<?php

namespace Akyos\ShopBundle\Form\Type\BaseUserShop;

use Akyos\FileManagerBundle\Form\Type\FileManagerType;
use Akyos\ShopBundle\Entity\BaseUserShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseUserShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email du client'
            ])
            ->add('image', FileManagerType::class, [
                'label' => 'Image du client'
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Client vérifié ?'
            ])
        ;
        if (!$data->getId()) {
            $builder
                ->add('password', PasswordType::class, [
                    'label' => 'Mot de passe'
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BaseUserShop::class,
        ]);
    }
}
