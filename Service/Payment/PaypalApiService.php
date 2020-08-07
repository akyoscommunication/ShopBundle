<?php


namespace Akyos\ShopBundle\Service\Payment;

use Akyos\ShopBundle\Entity\ShopOptions;
use App\Repository\Shop\OrderRepository;
use Akyos\ShopBundle\Repository\OrderStatusRepository;
use Akyos\ShopBundle\Repository\ShopOptionsRepository;
use Akyos\ShopBundle\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

class PaypalApiService
{
    /** @var EntityManagerInterface */
    private $em;
    /**
     * @var ShopOptions
     */
    private $shopOptions;
    private $mailer;
    private $url;
    private $orderRepository;
    private $orderStatusRepository;

    public function __construct(EntityManagerInterface $em, ShopOptionsRepository $shopOptions, Mailer $mailer, OrderRepository $orderRepository, OrderStatusRepository $orderStatusRepository)
    {
        $this->em = $em;
        $this->shopOptions = $shopOptions->findAll()[0];
        $this->mailer = $mailer;
        $this->url =  ($this->shopOptions->getPaypalSandbox() == true ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com');
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    public function getAccessToken()
    {
        $client = HttpClient::create();
        $response = $client->request('POST', $this->url.'/v1/oauth2/token', [
            'auth_basic' => $this->shopOptions->getPaypalPKey() . ':' . $this->shopOptions->getPaypalSKey(),
            'body' => ['grant_type' => 'client_credentials'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
                'Accept-Language' => 'en_US'
            ],
        ]);
        $this->shopOptions->setPaypalAccessToken(json_decode($response->getContent(), true)['access_token']);
        $this->em->flush();
    }

    public function createPayement($order)
    {
        $this->getAccessToken();
        $cartItems = "";
        $totalPrice = 0;

        foreach($order->getCart()->getCartItems() as $key => $item){
            if($key != 0){
                $cartItems .= ',';
            }
            $cartItems .= '{
                    "name": "'.$item->getProduct()->getName().'",
                    "unit_amount": {
                      "currency_code": "EUR",
                      "value": '.$item->getProduct()->getPrice().'
                    },
                    "quantity": "'.$item->getQty().'",
                    "category": "PHYSICAL_GOODS"
                  }';

            $totalPrice += $item->getProduct()->getPrice() * $item->getQty();
        }
        $order_json = '{
            "intent": "CAPTURE",
            "purchase_units": [
              {'.
                '"amount": {
                  "currency_code": "EUR",
                  "value": '.$totalPrice.',
                  "breakdown": {
                    "item_total": {
                      "currency_code": "EUR",
                      "value": '.$totalPrice.'
                    }'.
                  '}
                },
                "items": ['.
                  $cartItems
                .'],
                "shipping": {
                  "address": {
                    "address_line_1": "'.$order->getInvoiceAddress()->getAddress().'",
                    "address_line_2": "",
                    "admin_area_2": "'.$order->getInvoiceAddress()->getCity().'",
                    "postal_code": "'.$order->getInvoiceAddress()->getZip().'",
                    "country_code": "FR"
                  }
                }
              }
            ],
            "application_context": {
              "shipping_preference": "SET_PROVIDED_ADDRESS",
              "user_action": "PAY_NOW",
              "return_url": "https://localhost:8000/admin/commande/capture/payement"
            }
           }';
        $client = HttpClient::create();
        $response = $client->request('POST', $this->url.'/v2/checkout/orders', [
            'body' => $order_json,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
                'Authorization' => 'Bearer ' . $this->shopOptions->getPaypalAccessToken(),
                'Accept-Language' => 'en_US'
            ],
        ]);
        $order->setApiPayementId(json_decode($response->getContent(), true)['id']);
        $this->em->flush();
        $this->mailer->sendMessage($order->getClient()->getEmail(), 'Demande de payement de la commande N°'.$order->getRef(), '@AkyosShop/email/default.html.twig', $order,'Lien pour le payement: '.json_decode($response->getContent(), true)['links'][1]['href']);
    }

    public function capturePayment(Request $request)
    {
        $client = HttpClient::create();
        $response = $client->request('POST', $this->url.'/v2/checkout/orders/'.$request->get('token').'/capture', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->shopOptions->getPaypalAccessToken(),
            ],
        ]);
        $order = $this->orderRepository->findOneBy(['apiPayementId' => $request->get('token')]);

        if(json_decode($response->getContent(), true)['status'] == 'COMPLETED'){
            $order->setOrderStatus($this->orderStatusRepository->findOneBy(['id' => 4]));
        }else{
            $order->setOrderStatus($this->orderStatusRepository->findOneBy(['id' => 3]));
        }
        $this->em->flush();
        return $order;
    }
}