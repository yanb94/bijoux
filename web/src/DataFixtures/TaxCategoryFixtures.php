<?php

namespace App\DataFixtures;

use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Taxation\Model\TaxCategory;

class TaxCategoryFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        $taxCategory = new TaxCategory();

        $taxCategory->setCode("VAT");
        $taxCategory->setName("TVA");

        $manager->persist($taxCategory);
        $manager->flush();
    }
}
