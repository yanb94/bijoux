<?php

namespace App\DataFixtures;

use App\Entity\Locale\Locale;
use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;


class LocaleFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        $localeRepository = $manager->getRepository(Locale::class);

        $locale = $localeRepository->findOneBy(['code' => "fr_FR"]);

        if(!is_null($locale)) return;
        
        $locale = new Locale();
        $locale->setCode("fr_FR");

        $manager->persist($locale);
        $manager->flush();
    }
}
