<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Project name',
                'attr' => [
                    'placeholder' => 'Enter project name',
                ],
            ])
//            ->add('customSlug', CheckboxType::class, [
//                'label' => 'Custom slug',
//                'required' => false,
//            ])
//            ->add('slug', TextType::class, [
//                'label' => false,
//                'required' => false,
//                'attr' => [
//                    'readonly' => true,
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
