<?php

namespace App\DataFixtures;

use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Addressing\Factory\ZoneFactoryInterface;

class ZoneFixtures extends InitFixtures
{
    private $factory;

    public function __construct(ZoneFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function load(ObjectManager $manager): void
    {
        $zone = $this->factory->createWithMembers([
            "FR",

            "AT",
            "BE",
            "HR",
            "CY",
            "CZ",
            "DK",
            "EE",
            "FI",
            "DE",
            "GR",
            "HU",
            "IE",
            "IT",
            "LV",
            "LT",
            "LU",
            "MT",
            "NL",
            "PL",
            "PT",
            "RO",
            "SK",
            "SI",
            "ES",
            "SE"
        ]);

        $zone->setCode("EU");
        $zone->setName("Union Européenne");
        $zone->setType("country");
        $zone->setScope("all");

        $manager->persist($zone);

        $manager->flush();
    }
}
