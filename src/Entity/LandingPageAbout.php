<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
//use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class LandingPageAbout
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

//    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $columnLeft = null;

//    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $columnRight = null;

    public function getId(): ?int
    {
        return $this->id;
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
