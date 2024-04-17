<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class LandingPageAbout
{
    private ?string $subtitle = null;

    private ?string $columnLeft = null;

    private ?string $columnRight = null;

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getColumnLeft(): ?string
    {
        return $this->columnLeft;
    }

    public function setColumnLeft(?string $columnLeft): static
    {
        $this->columnLeft = $columnLeft;

        return $this;
    }

    public function getColumnRight(): ?string
    {
        return $this->columnRight;
    }

    public function setColumnRight(?string $columnRight): static
    {
        $this->columnRight = $columnRight;

        return $this;
    }
}
