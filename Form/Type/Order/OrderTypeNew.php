<?php

namespace Akyos\ShopBundle\Form\Type\Order;

use App\Entity\Shop\Order;
use Akyos\ShopBundle\Entity\PaymentType;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderTypeNew extends AbstractType
{
    /** @var PaymentTypeRepository */
    private $paymentTypeRepository;

    public function __construct(PaymentTypeRepository $paymentTypeRepository)
    {
        $this->paymentTypeRepository = $paymentTypeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Order $order */
        $order = $builder->getData();

        $builder
            ->add('orderStatus', null, [
                'label' => "Statut de la commande",
                'block_prefix' => 'order_status',
            ])
            ->add('shippingMode', null, [
                'label' => "Mode de livraison"
            ])
        ;
        if ($order->getClient()) {
            $builder
                ->add('invoiceAddress', null, [
                    'label' => "Adresse de facturation",
                    'choices' => $order->getClient()->getAddresses(),
                    'multiple' => false,
                    'expanded' => false,
                    'block_prefix' => 'invoice_address',
                    'choice_label' => function ($choice) {
                        return $choice->getTitle();
                    },
                ])
                ->add('deliveryAddress', null, [
                    'label' => "Adresse de livraison",
                    'choices' => $order->getClient()->getAddresses(),
                    'multiple' => false,
                    'expanded' => false,
                    'block_prefix' => 'delivery_address',
                    'choice_label' => function ($choice) {
                        return $choice->getTitle();
                    },
                ])
            ;
        }
        $builder->add('payment', \Akyos\ShopBundle\Form\Type\Payment\PaymentType::class, [
            'label' => "Paiement de la commande",
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            "translation_domain" => "forms",
        ]);
    }
}
