<?php

namespace App\Form;

use App\Entity\Legal;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => "sylius.ui.title"
                ])
            ->add('titleList',TextType::class,[
                "label" => "sylius.ui.title_list"
            ])
            ->add('content',CKEditorType::class,[
                "label" => "sylius.ui.content"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Legal::class,
        ]);
    }
}
