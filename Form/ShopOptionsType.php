<?php

namespace Akyos\ShopBundle\Form;

use Akyos\FileManagerBundle\Form\Type\FileManagerType;
use Akyos\ShopBundle\Entity\ShippingMode;
use Akyos\ShopBundle\Entity\ShopOptions;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paypalPayment', CheckboxType::class, [
                'label' => 'Payement par Paypal',
                'required' => false
            ])
        ;

        $formModifier = function (FormInterface $form, FormEvent $event) {
            if($event->getData()->getPaypalPayment()){
                $form
                    ->add('paypalSandbox', CheckboxType::class, [
                        'label' => 'Paypal en mode test',
                        'required' => false
                    ])
                    ->add('paypalPKey', TextType::class, [
                        'label' => 'Client ID',
                        'required' => false
                    ])
                    ->add('paypalSKey', TextType::class, [
                        'label' => 'Cléf secrete Paypal',
                        'required' => false
                    ])
                ;
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier, $options) {
                $formModifier($event->getForm(), $event);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShopOptions::class
        ]);
    }
}
