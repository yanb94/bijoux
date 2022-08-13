<?php

namespace App\DataFixtures;

use App\Entity\Channel\Channel;
use App\DataFixtures\InitFixtures;
use App\Entity\Payment\GatewayConfig;
use App\Entity\Payment\PaymentMethod;
use App\DataFixtures\ChannelsFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentMethodFixtures extends InitFixtures implements DependentFixtureInterface
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function load(ObjectManager $manager): void
    {
        $channelRepository = $manager->getRepository(Channel::class);
        $channel = $channelRepository->findOneBy(['code' => 'default']);

        $gatewayConfig = new GatewayConfig();

        $gatewayConfig->setConfig([
            "publishable_key" => $this->params->get("stripe_public"),
            "secret_key" => $this->params->get("stripe_private")
        ]);
        $gatewayConfig->setFactoryName("stripe_checkout");
        $gatewayConfig->setGatewayName("stripe_checkout");

        $paymentMethod = new PaymentMethod();

        $paymentMethod->setCurrentLocale("fr_FR");
        $paymentMethod->setFallbackLocale("fr_FR");

        $paymentMethod->addChannel($channel);
        $paymentMethod->setCode("STRIPE");
        $paymentMethod->setName("Stripe");
        $paymentMethod->setDescription("Stripe est une société américaine d'origine irlandaise, destinée au paiement par internet pour professionnels.");
        $paymentMethod->setEnabled(true);

        $paymentMethod->setGatewayConfig($gatewayConfig);

        $manager->persist($paymentMethod);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ChannelsFixtures::class,
        ];
    }
}
