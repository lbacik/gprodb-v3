<?php

declare(strict_types=1);

namespace App\Type;

use Symfony\Component\Validator\Constraints as Assert;

class Link
{
    private string|null $name = null;

    #[Assert\Url]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    private string $url;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        return (new static())
            ->setName($data['name'] ?? null)
            ->setUrl($data['url']);
    }
}
