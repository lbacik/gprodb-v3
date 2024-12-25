<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Project;
use App\Type\LinkCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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

        $this->addTransformers($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }

    private function addTransformers(FormBuilderInterface $builder): void
    {
        $builder->get('links')
            ->addModelTransformer(new CallbackTransformer(
                function (LinkCollection|null $links) {
                    return $links;
                },
                function (array $links) {
                    return LinkCollection::fromArray($links);
                }
            ));
    }
}
