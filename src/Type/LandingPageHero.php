<?php

declare(strict_types=1);

namespace App\Type;

use Symfony\Component\Validator\Constraints as Assert;

class LandingPageHero
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    private ?string $title = null;

    private ?string $subtitle = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }
}
