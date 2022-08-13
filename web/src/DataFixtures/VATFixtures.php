<?php

namespace App\DataFixtures;

use App\Entity\Taxation\TaxRate;
use App\DataFixtures\InitFixtures;
use App\Entity\Taxation\TaxCategory;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\TaxCategoryFixtures;
use App\Entity\Addressing\Zone;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VATFixtures extends InitFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $taxCategoryRepository = $manager->getRepository(TaxCategory::class);
        $taxCategory = $taxCategoryRepository->findOneBy(['code' => "VAT"]);

        $zoneRepository = $manager->getRepository(Zone::class);
        $zone = $zoneRepository->findOneBy(['code' => "EU"]);

        $tax = new TaxRate();
        
        $tax->setAmount(0.2);
        $tax->setCode("VAT");
        $tax->setCalculator("default");
        $tax->setName("Tva");
        $tax->setIncludedInPrice(false);
        $tax->setCategory($taxCategory);
        $tax->setZone($zone);
            
        $manager->persist($tax);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TaxCategoryFixtures::class,
            ZoneFixtures::class
        ];
    }
}
