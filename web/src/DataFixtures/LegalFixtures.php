<?php

namespace App\DataFixtures;

use App\Entity\Legal;
use App\DataFixtures\InitFixtures;
use Doctrine\Persistence\ObjectManager;

class LegalFixtures extends InitFixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getListOfLegal() as $legalData) {
            
            $legal = new Legal();

            $legal->setName($legalData['title']);
            $legal->setTitleList($legalData['list_title']);
            $legal->setContent(file_get_contents(__DIR__. '/../../filesFixtures/legalFixtures.txt'));

            $manager->persist($legal);
        }
        
        $manager->flush();
    }


    private function getListOfLegal(): array
    {
        return [
            [
                "title" => "Politique de confiendentialité",
                "list_title" => "Confidentialité"
            ],
            [
                "title" => "Conditions générale d'utilisation",
                "list_title" => "C.G.U"
            ],
        ];
    }
}
