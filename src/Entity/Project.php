<?php

declare(strict_types=1);

namespace App\Entity;

class Project
{
    private ?string $id = null;

    private ?string $name = null;

    private ?array $description = null;

    public function getId(): ?string
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

    public static function createFromJson(string $id, array $data): static
    {
        $project = new self();
        $project->id = $id;
        $project->name = $data['name'];
        $project->description = $data['description'];

        return $project;
    }
}
