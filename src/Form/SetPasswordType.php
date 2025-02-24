<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetPasswordType extends AbstractType
{
    public const PASSWORD = 'password';

    public const CONFIRM_PASSWORD = 'confirm_password';

    public const TOKEN = 'token';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'first_name' => self::PASSWORD,
                'second_name' => self::CONFIRM_PASSWORD,
                'type' => PasswordType::class,
            ])
        ;

        if (isset($options['data']['token'])) {
            $builder->add('token', HiddenType::class, []);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
