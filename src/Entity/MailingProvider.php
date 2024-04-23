<?php

declare(strict_types=1);

namespace App\Entity;

use App\Type\MailingProvider as MailingProviderEnum;

class MailingProvider
{
    private string|null $id;

    private MailingProviderEnum $name = MailingProviderEnum::GENERIC;

    private string $parent;

    public function __construct(string|null $id = null)
    {
        $this->id = $id;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getName(): MailingProviderEnum
    {
        return $this->name;
    }

    public function setName(MailingProviderEnum $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getNameString(): string
    {
        return $this->name->value;
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
