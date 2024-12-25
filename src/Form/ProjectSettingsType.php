<?php

declare(strict_types=1);

namespace App\Form;

use App\Type\ProjectSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'];

        if ($data->getLandingPageEntityId() === null) {
            $builder
                ->add('createLandingPage', ButtonType::class, [
                    'label' => 'Create landing page',
                ]);
        } else {
            $builder
                ->add('landingPage');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectSettings::class,
        ]);
    }
}
