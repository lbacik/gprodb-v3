<?php

declare(strict_types=1);

namespace App\Entity;

class Project
{
    private ?int $id = null;

    private ?string $name = null;

    private ?array $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?array
    {
        return $this->description;
    }

    public function setDescription(?array $description): static
    {
        $this->description = $description;

        return $this;
    }
}
