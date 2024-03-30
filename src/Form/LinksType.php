<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LinksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'hidden' => true,
                ],
            ])
            ->add('links', CollectionType::class, [
                'row_attr' => [
                    'class' => 'links-collection-row',
                ],
                'attr' => [
                    'class' => 'links-collection',
                ],
                'entry_type' => LinkType::class,
                'entry_options' => [
                    'label' => false,
                    'row_attr' => [
                        'class' => 'link-element',
                    ],
                    'attr' => [
                        'class' => 'link-row',
                    ],
                    'constraints' => [
                        new Assert\Valid(),
                    ],
                ],
                'allow_add' => true,
                'allow_delete' => false,
                'by_reference' => true,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
