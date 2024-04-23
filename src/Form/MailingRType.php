<?php

namespace App\Form;

use App\Entity\MailingRConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailingRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productId', TextType::class, [
                'label' => 'Product ID',
            ])
            ->add('apiKey', PasswordType::class, [
                'label' => 'API Key',
                'always_empty' => false,
                'attr' => [
                    'autocomplete' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailingRConfig::class,
        ]);
    }
}
