<?php

declare(strict_types=1);

namespace App\Twig\Components\Footer;

use App\Form\MailingSubscriptionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Newsletter
{
    public FormView $form;

    public function __construct(
        private readonly FormFactoryInterface $formFactory,
    ) {
        $this->form = $this->buildForm();
    }

    private function buildForm(): FormView
    {
        return $this->formFactory->create(MailingSubscriptionType::class, null, [
            'action' => '/subscribe',
        ])->createView();
    }
}
