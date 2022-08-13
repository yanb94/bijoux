<?php

namespace App\DataFixtures;

use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Shipping\ShippingCategory;


class ShippingCategoriesFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        $shippingCategory = new ShippingCategory();
        $shippingCategory->setCode("BIJOUX");
        $shippingCategory->setName("Bijoux");
        $shippingCategory->setDescription("C'est la catégorie d'expédition des bijoux");

        $manager->persist($shippingCategory);
        $manager->flush();
    }
}
