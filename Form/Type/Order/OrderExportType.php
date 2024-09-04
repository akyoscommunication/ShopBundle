<?php

namespace Akyos\ShopBundle\Form\Type\Order;

use App\Entity\Shop\Order;
use Akyos\ShopBundle\Entity\PaymentType;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('beginAt', DateType::class, [
                'label' => "Commence à",
                'widget' => 'single_text'
            ])
            ->add('endAt', DateType::class, [
                'label' => "Fini à",
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "translation_domain" => "forms",
        ]);
    }
}
