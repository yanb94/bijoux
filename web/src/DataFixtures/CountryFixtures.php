<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Addressing\Model\Country;

class CountryFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getListOfCountriesinEU() as $countryCode) {
            $country = new Country();

            $country->setCode($countryCode);
            $country->setEnabled(true);

            $manager->persist($country);
        }

        $manager->flush();
    }

    public function getListOfCountriesinEU(): array
    {
        return [
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
        ];
    }
}
