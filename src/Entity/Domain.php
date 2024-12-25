<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Domain
{
    private string|null $id;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private string $domain;

    public function __construct(string $id = null)
    {
        $this->id = $id;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }
}
