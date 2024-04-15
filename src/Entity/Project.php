<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Project
{
    private ?string $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $name;

    private ?string $description = null;

    private array $links;

    private ?User $owner = null;

    private ?ProjectSettings $settings = null;

    public function __construct()
    {
        $this->links = [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string|null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

//    public function addLink(Link $link): static
//    {
//        if (!$this->links->contains($link)) {
//            $this->links->add($link);
//            $link->setProject($this);
//        }
//
//        return $this;
//    }
//
//    public function removeLink(Link $link): static
//    {
//        if ($this->links->removeElement($link)) {
//            // set the owning side to null (unless already changed)
//            if ($link->getProject() === $this) {
//                $link->setProject(null);
//            }
//        }
//
//        return $this;
//    }

    public function setLinks(array $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSettings(): ?ProjectSettings
    {
        return $this->settings;
    }

    public function setSettings(?ProjectSettings $settings): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'links' => $this->links,
        ];
    }
}
