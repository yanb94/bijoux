<?php

namespace App\Generator;

use Webmozart\Assert\Assert;
use Sylius\RefundPlugin\Entity\CreditMemoUnit;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\RefundPlugin\Entity\CreditMemoUnitInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\RefundPlugin\Generator\CreditMemoUnitGeneratorInterface;
use Sylius\Component\Order\Model\AdjustmentInterface as OrderAdjustmentInterface;

final class ShipmentCreditMemoUnitGenerator implements CreditMemoUnitGeneratorInterface
{
    /** @var RepositoryInterface */
    private $adjustmentRepository;

    public function __construct(RepositoryInterface $adjustmentRepository)
    {
        $this->adjustmentRepository = $adjustmentRepository;
    }

    public function generate(int $unitId, int $amount = null): CreditMemoUnitInterface
    {
        /** @var OrderAdjustmentInterface $shippingAdjustment */
        $shippingAdjustment = $this
            ->adjustmentRepository
            ->findOneBy(['id' => $unitId, 'type' => AdjustmentInterface::SHIPPING_ADJUSTMENT])
        ;
        Assert::notNull($shippingAdjustment);

        $creditMemoUnitTotal = $this->getCreditMemoUnitTotal($shippingAdjustment, $amount);

        return new CreditMemoUnit($shippingAdjustment->getLabel(), $creditMemoUnitTotal, 0);
    }

    private function getCreditMemoUnitTotal(OrderAdjustmentInterface $shippingAdjustment, int $amount = null): int
    {
        $goodAmount = $this->getGoodAmount($shippingAdjustment);

        Assert::lessThanEq($amount, $goodAmount);
        $creditMemoUnitTotal = null === $amount ? $goodAmount : $amount;

        return $creditMemoUnitTotal;
    }

    // Correct problem with tax and promotion
    private function getGoodAmount(OrderAdjustmentInterface $shippingAdjustment)
    {
        $order = $shippingAdjustment->getAdjustable();
        if ($order instanceof OrderInterface) {
            return $order->getShippingTotal();
        }

        return $shippingAdjustment->getAmount();
    }
}
