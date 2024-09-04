<?php

namespace Akyos\ShopBundle\Form\Type\Address;

use Akyos\ShopBundle\Entity\ShopAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'adresse",
                'required' => true,
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom",
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom",
                'required' => true,
            ])
            ->add('company', TextType::class, [
                'label' => "Entreprise"
            ])
            ->add('vatNumber', TextType::class, [
                'label' => "Numéro de TVA"
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse",
                'required' => true,
            ])
            ->add('addressTwo', TextType::class, [
                'label' => "Deuxième adresse (facultatif)"
            ])
            ->add('zip', TextType::class, [
                'label' => "Code postal",
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'required' => true,
            ])
            ->add('country', TextType::class, [
                'label' => "Pays",
                'required' => true,
            ])
            ->add('homephone', TelType::class, [
                'label' => "Téléphone fixe"
            ])
            ->add('cellphone', TelType::class, [
                'label' => "Téléphone portable",
                'required' => true,
            ])
            ->add('other', TextType::class, [
                'label' => "Autre ..."
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShopAddress::class,
        ]);
    }
}
