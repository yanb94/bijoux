<?php

namespace App\Refund;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentMethodRepository;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Stripe\Refund;
use Stripe\Stripe;

class StripeRefundProcessor
{
    private $paymentMethodRepository;
    private $orderRepository;

    public function __construct(
        PaymentMethodRepository $paymentMethodRepository,
        OrderRepository $orderRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->orderRepository = $orderRepository;
    }

    public function processor(UnitsRefunded $event)
    {
        // Retrieve Payement Object
        $payementMethodId = $event->paymentMethodId();        
        /** @var PaymentMethod */
        $payementMethod = $this->paymentMethodRepository->findOneBy(['id' => $payementMethodId]);

        // Retrieve Api Key From Stripe Config
        $config = $payementMethod->getGatewayConfig()->getConfig();
        $publicKey = $config['publishable_key'];
        $privateKey = $config['secret_key'];

        // Retrieve amount to refund
        $amount = $event->amount();

        // Retrieve Order Object
        $orderNumber = $event->orderNumber();
        /** @var Order */
        $order = $this->orderRepository->findOneBy(['number' => $orderNumber]);

        // Retrieve Payment Object
        $payment = $order->getLastPayment();

        // Retrieve Id of stripe transaction
        $details = $payment->getDetails();
        $chargeId = $details["id"];

        // Refund the customer
        Stripe::setApiKey($privateKey);
        Refund::create([
            'amount' => $amount,
            'charge' => $chargeId
        ]);
    }
}