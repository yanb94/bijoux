<?php

namespace App\DataFixtures;

use App\Entity\Addressing\Zone;
use App\Entity\Channel\Channel;
use App\DataFixtures\InitFixtures;
use App\Entity\Taxation\TaxCategory;
use App\Entity\Shipping\ShippingMethod;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Shipping\ShippingCategory;
use App\DataFixtures\ShippingCategoriesFixtures;
use App\Entity\Shipping\ShippingMethodTranslation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;

class ShippingMethodFixtures extends InitFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $shippingCategoryRepository = $manager->getRepository(ShippingCategory::class);
        $shippingCategory = $shippingCategoryRepository->findOneBy(['code' => "BIJOUX"]);

        $zoneRepository = $manager->getRepository(Zone::class);
        $zone = $zoneRepository->findOneBy(['code' => "EU"]);

        $taxCategoryRepository = $manager->getRepository(TaxCategory::class);
        $taxCategory = $taxCategoryRepository->findOneBy(['code' => "VAT"]);

        $channelRepository = $manager->getRepository(Channel::class);
        $channel = $channelRepository->findOneBy(['code' => 'default']);

        foreach ($this->getListOfShippingMethod() as $shippingMethodData) {
            $shippingMethod = $this->createShippingMethod(
                $shippingCategory,
                $zone,
                $taxCategory,
                $channel,
                $shippingMethodData
            );

            $manager->persist($shippingMethod);
        }

        $manager->flush();
    }

    private function createShippingMethod(
        ShippingCategory $shippingCategory, 
        Zone $zone, 
        TaxCategory $taxCategory, 
        Channel $channel, 
        array $infos
    ): ShippingMethod
    {
        $shippingMethodTranslation = new ShippingMethodTranslation();
        $shippingMethodTranslation->setLocale("fr_FR");
        $shippingMethodTranslation->setName($infos['name']);
        $shippingMethodTranslation->setDescription($infos['description']);

        $shippingMethod = new ShippingMethod();

        $shippingMethod->setCurrentLocale("fr_FR");
        $shippingMethod->setFallbackLocale("fr_FR");
        $shippingMethod->setCode($infos['code']);
        $shippingMethod->setCalculator("flat_rate");
        $shippingMethod->setCategory($shippingCategory);
        $shippingMethod->setCategoryRequirement(ShippingMethodInterface::CATEGORY_REQUIREMENT_MATCH_ALL);
        $shippingMethod->setZone($zone);
        $shippingMethod->setTaxCategory($taxCategory);
        $shippingMethod->setName($infos['name']);
        $shippingMethod->setDescription($infos['description']);
        $shippingMethod->setEnabled(true);
        $shippingMethod->addChannel($channel);
        $shippingMethod->setConfiguration([
            "default" => [
                "amount" => $infos['amount']
            ]
        ]);
        $shippingMethod->addTranslation($shippingMethodTranslation);

        return $shippingMethod;
    }

    private function getListOfShippingMethod(): array
    {
        return [
            [
                "code" => "CHRONOPOST",
                "name" => "Chronospost",
                "description" => "Chronospost est un service de livraison",
                "amount" => 100
            ],
            [
                "code" => "FEDEX",
                "name" => "FedEx",
                "description" => "FedEx est un service de livraison",
                "amount" => 250
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            ShippingCategoriesFixtures::class,
            ZoneFixtures::class,
            TaxCategoryFixtures::class,
            ChannelsFixtures::class
        ];
    }
}
