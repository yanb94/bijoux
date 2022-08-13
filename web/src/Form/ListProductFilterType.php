<?php

namespace App\Form;

use App\Entity\Taxonomy\Taxon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('search',SearchType::class,[
            //     "label" => false,
            //     "attr" => [
            //         "placeholder" => "Recherche"
            //     ]
            // ])
            ->setMethod("GET")
            ->add('price_min',NumberType::class,[
                "label" => false,
                "attr" => [
                    "placeholder" => "Minimum"
                ],
                "required" => false
            ])
            ->add('price_max',NumberType::class,[
                "label" => false,
                "attr" => [
                    "placeholder" => "Maximum"
                ],
                "required" => false
            ])
            ->add('categories', EntityType::class,[
                "class" => Taxon::class,
                "choice_label" => "name",
                "expanded" => true,
                "multiple" => true,
                "label" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'csrf_protection' => false
        ]);
    }
}
