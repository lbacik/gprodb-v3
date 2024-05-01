<?php

declare(strict_types=1);

namespace App\Entity;

use App\Type\MailingProvider as MailingProviderEnum;

class MailingProvider
{
    private string|null $id;

    private MailingProviderEnum $newsletter = MailingProviderEnum::GENERIC;

    private string $parent;

    public function __construct(string|null $id = null)
    {
        $this->id = $id;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getNewsletter(): MailingProviderEnum
    {
        return $this->newsletter;
    }

    public function setNewsletter(MailingProviderEnum $newsletter): static
    {
        $this->newsletter = $newsletter;
        return $this;
    }

    public function getNameString(): string
    {
        return $this->newsletter->value;
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function setParent(string $parent): static
    {
        $this->parent = $parent;
        return $this;
    }
}
