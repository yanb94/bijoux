<?php

namespace App\DataFixtures;

use App\Entity\Currency\Currency;
use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;


class CurrencyFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        $currencyRepository = $manager->getRepository(Currency::class);
        $currency = $currencyRepository->findOneBy(['code' => "EUR"]);

        if(!is_null($currency)) return;

        $currency = new Currency();
        $currency->setCode("EUR");

        $manager->persist($currency);
        $manager->flush();
    }
}
