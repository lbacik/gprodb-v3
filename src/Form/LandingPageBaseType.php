<?php

namespace App\Form;

use App\Entity\LandingPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandingPageBaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Internal name',
                ],
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Visible in browser tab',
                ],
            ])
            ->add('contact', CheckboxType::class, [
                'label' => 'Show contact form',
                'required' => false,
            ])
            ->add('contactInfo', TextareaType::class, [
                'label' => 'Contact info',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'data-markdown-editor-target' => 'editor',
                    'data-toolbar' => 'simple',
                ],
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'Show newsletter form',
                'required' => false,
            ])
            ->add('newsletterInfo', TextareaType::class, [
                'label' => 'Newsletter info',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'data-markdown-editor-target' => 'editor',
                    'data-toolbar' => 'simple',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LandingPage::class,
        ]);
    }
}
