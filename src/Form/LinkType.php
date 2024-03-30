<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Link;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('url', UrlType::class)
            ->add('delete', ButtonType::class, [
                'row_attr' => [
                    'class' => 'link-delete-button-cell',
                ],
                'attr'=> [
                    'class' => 'link-delete-button',
                    'data-action' => 'click->form-collection#delete',
                ],
                'label' => '<span class="fa-solid fa-trash-can"></span>',
                'label_html' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
