<?php

namespace App\EventSubscriber;

use App\Entity\Order\Order;
use App\Entity\Payment\PaymentMethod;
use App\Refund\StripeRefundProcessor;
use Stripe\Refund;
use Stripe\Stripe;
use Sylius\RefundPlugin\Event\UnitsRefunded;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class UnitsRefundedSubscriber implements MessageSubscriberInterface
{

    private $stripeRefundProcessor;

    public function __construct(StripeRefundProcessor $stripeRefundProcessor)
    {
        $this->stripeRefundProcessor = $stripeRefundProcessor;
    }

    public function onUnitsRefunded(UnitsRefunded $event)
    {
        $this->stripeRefundProcessor->processor($event);
    }

    public static function getHandledMessages(): iterable
    {
        yield UnitsRefunded::class => [
            "method" => 'onUnitsRefunded'
        ];
    }
}
