<?php

namespace App\Refund;

use Exception;
use Webmozart\Assert\Assert;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\RefundPlugin\Provider\RefundPaymentMethodsProviderInterface;

final class StripeRefundPayementMethodsProvider implements RefundPaymentMethodsProviderInterface
{

    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function findForChannel(ChannelInterface $channel): array
    {
        return array_filter(
            $this->paymentMethodRepository->findEnabledForChannel($channel),
            function (PaymentMethodInterface $paymentMethod): bool {
                Assert::notNull($paymentMethod->getGatewayConfig());

                return $paymentMethod->getGatewayConfig()->getFactoryName() === 'stripe_checkout';
            }
        );
    }
}