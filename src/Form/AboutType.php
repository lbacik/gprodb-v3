<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subtitle')
            ->add('columnLeft', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
                    'data-markdown-editor-target' => 'editor',
                ],
            ])
            ->add('columnRight', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
                    'data-markdown-editor-target' => 'editor',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
