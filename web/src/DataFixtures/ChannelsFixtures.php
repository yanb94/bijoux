<?php

namespace App\DataFixtures;

use App\Entity\Locale\Locale;
use App\Entity\Addressing\Zone;
use App\Entity\Channel\Channel;
use App\Entity\Currency\Currency;
use App\DataFixtures\InitFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\ShopBillingData;

class ChannelsFixtures extends InitFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $localeRepository = $manager->getRepository(Locale::class);
        $locale = $localeRepository->findOneBy(['code' => 'fr_FR']);

        $currencyRepository = $manager->getRepository(Currency::class);
        $currency = $currencyRepository->findOneBy(['code' => 'EUR']);

        $zoneRepository = $manager->getRepository(Zone::class);
        $zone = $zoneRepository->findOneBy(['code' => "EU"]);

        /** @var Channel */
        $channel = $manager->getRepository(Channel::class)->findOneBy(['code' => "default"]);

        $channel->setName("Éclat");
        $channel->setDescription("Éclat est un magasin de joaillerie, qui propose un vaste de choix de bijoux de qualité.");
        $channel->setThemeName("yanb94/bijoux-theme");
        $channel->setSkippingPaymentStepAllowed(false);
        $channel->setAccountVerificationRequired(true);
        $channel->setSkippingShippingStepAllowed(false);

        $channel->setContactEmail("example@example.com");
        $channel->setColor("#121111");

        $channel->setDefaultLocale($locale);
        $channel->addLocale($locale);
        $channel->setBaseCurrency($currency);
        $channel->setDefaultTaxZone($zone);

        $channel->setEnabled(true);

        $shopBillingData = new ShopBillingData();

        $shopBillingData->setCity("Paris");
        $shopBillingData->setCompany("Exmaple Corp");
        $shopBillingData->setCountryCode("FR");
        $shopBillingData->setPostcode("75000");
        $shopBillingData->setTaxId("0237744647772");
        $shopBillingData->setStreet("Place de l'Hôtel de Ville");

        $channel->setShopBillingData($shopBillingData);

        $manager->persist($channel);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LocaleFixtures::class,
            CurrencyFixtures::class,
            ZoneFixtures::class,
        ];
    }
}
