<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sylius\Bundle\CoreBundle\Form\Type\ContactType as ParentContactType;

final class ContactType extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class,[
                "label" => "Nom et Prénom",
                "constraints" => [
                    new NotBlank([
                        'message' => 'Vous devez indiquer vos nom et prénom',
                    ])
                ]
            ])
            ->remove("email")
            ->add("email",RepeatedType::class,[
                "type" => EmailType::class,
                'invalid_message' => 'Vos emails doivent être identique',
                'required' => true,
                'first_options'  => [
                    'label' => 'sylius.ui.email',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'sylius.contact.email.not_blank',
                        ]),
                        new Email([
                            'message' => 'sylius.contact.email.invalid',
                        ]),
                    ]
                ],
                'second_options' => ['label' => 'Confirmer votre email'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'sylius.ui.message',
                'constraints' => [
                    new NotBlank([
                        'message' => 'sylius.contact.message.not_blank',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options): void {
                $email = $options['email'];
                if (null === $email) {
                    return;
                }

                $data = $event->getData();
                $data['email'] = $email;

                $event->setData($data);
            })

        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ParentContactType::class];
    }
}
