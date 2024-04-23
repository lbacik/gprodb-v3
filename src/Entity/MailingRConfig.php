<?php

declare(strict_types=1);

namespace App\Entity;

class MailingRConfig
{
    private string|null $id;

    private string $apiKey;

    private string $productId;

    private string $parent;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function __construct(string $id = null)
    {
        $this->id = $id;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): static
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): static
    {
        $this->productId = $productId;
        return $this;
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function setParent(string $parent): MailingRConfig
    {
        $this->parent = $parent;
        return $this;
    }
}
