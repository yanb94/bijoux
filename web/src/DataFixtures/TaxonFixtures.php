<?php

namespace App\DataFixtures;

use App\Entity\Taxonomy\Taxon;
use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Taxonomy\TaxonTranslation;

class TaxonFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getListOfTaxon() as $taxonData) {
            
            $taxon = $this->createTaxon($taxonData);
            $manager->persist($taxon);
        }

        $manager->flush();
    }

    private function createTaxon(array $data): Taxon
    {
        $taxonTranslation = new TaxonTranslation();

        $taxonTranslation->setDescription($data['description']);
        $taxonTranslation->setName($data['name']);
        $taxonTranslation->setLocale("fr_FR");
        $taxonTranslation->setSlug($data['slug']);

        $taxon = new Taxon();

        $taxon->setCurrentLocale("fr_FR");
        $taxon->setFallbackLocale("fr_FR");
        $taxon->setCode($data['code']);
        $taxon->setName($data['name']);
        $taxon->setEnabled(true);
        $taxon->setDescription($data['description']);
        $taxon->setSlug($data['slug']);

        $taxon->addTranslation($taxonTranslation);

        return $taxon;
    }

    private function getListOfTaxon(): array
    {
        return [
            [
                "code" => "COLLIER",
                "name" => "Collier",
                "description" => "",
                "slug" => "collier"
            ],
            [
                "code" => "BRACELET",
                "name" => "Bracelet",
                "description" => "",
                "slug" => "bracelet"
            ],
            [
                "code" => "MONTRE",
                "name" => "Montre",
                "description" => "",
                "slug" => "montre"
            ],
            [
                "code" => "BAGUE",
                "name" => "Bague",
                "description" => "",
                "slug" => "bague"
            ]
        ];
    }
}
