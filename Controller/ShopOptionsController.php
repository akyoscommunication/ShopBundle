<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Form\BuilderOptionsType;
use Akyos\BuilderBundle\Repository\BuilderOptionsRepository;
use Akyos\ShopBundle\Entity\PaymentType;
use Akyos\ShopBundle\Entity\ShippingMode;
use Akyos\ShopBundle\Entity\ShopOptions;
use Akyos\ShopBundle\Form\ShopOptionsType;
use Akyos\ShopBundle\Repository\PaymentTypeRepository;
use Akyos\ShopBundle\Repository\ShopOptionsRepository;
use Akyos\ShopBundle\Service\Payment\PaypalApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/shop/options", name="shop_options")
 */
class ShopOptionsController extends AbstractController
{
    /**
     * @Route("/", name="", methods={"GET", "POST"})
     * @param ShopOptionsRepository $shopOptionsRepository
     * @param PaymentTypeRepository $paymentTypeRepository
     * @param Request $request
     * @return Response
     */
    public function index(ShopOptionsRepository $shopOptionsRepository, PaymentTypeRepository $paymentTypeRepository,Request $request): Response
    {
        $shopOptions = $shopOptionsRepository->findAll();
        if(!$shopOptions) {
            $shopOptions = new ShopOptions();
        }else{
            $shopOptions = $shopOptions[0];
        }

        if($request->isMethod('POST')){
            foreach ($request->request->get('shop_options') as $key => $value){
                $methodSet = 'set'.ucfirst($key);
                switch($key){
                    case '_token':
                        break;
                    default:
                            $shopOptions->{$methodSet}($value);
                        break;
                }
            }
            if(!isset($request->request->get('shop_options')['paypalPayment'])){
                $shopOptions->setPaypalPayment(false);
            }
        }

        $form = $this->createForm(ShopOptionsType::class, $shopOptions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($shopOptions->getPaypalPayment()){
                if(!$paymentTypeRepository->findOneBy(['title' => 'Paypal'])){
                    $paymentType = new PaymentType();
                    $paymentType->setTitle('Paypal');
                    $this->getDoctrine()->getManager()->persist($paymentType);
                    $this->getDoctrine()->getManager()->flush();
                }
            }else{
                if($paymentTypeRepository->findOneBy(['title' => 'Paypal'])){
                    $this->getDoctrine()->getManager()->remove($paymentTypeRepository->findOneBy(['title' => 'Paypal']));
                }
            }

            $entityManager->persist($shopOptions);
            $entityManager->flush();

            return $this->redirectToRoute('shop_options');
        }

        return $this->render('@AkyosShop/shop_options/edit.html.twig', [
            'shop_option' => $shopOptions,
            'form' => $form->createView(),
        ]);
    }
}
