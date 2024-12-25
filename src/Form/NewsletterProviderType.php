<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\MailingProvider;
use App\Type\MailingProvider as MailingProviderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newsletter', ChoiceType::class, [
                'choices' => array_reduce(
                    MailingProviderEnum::values(),
                    fn (array $choices, string $name) => array_merge($choices, [$name => $name]),
                    [],
                ),
                'label' => 'Newsletter provider',
            ])
        ;

        $builder->get('newsletter')
            ->addModelTransformer(
                new CallbackTransformer(
                    fn (MailingProviderEnum|null $name) => $name?->value,
                    fn (string $name) => MailingProviderEnum::from($name),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MailingProvider::class,
        ]);
    }
}
