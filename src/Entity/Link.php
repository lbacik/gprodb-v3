<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Link
{
//    private ?int $id = null;

    private string|null $name = null;

    #[Assert\Url]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    private string $url;

//    public function getId(): ?int
//    {
//        return $this->id;
//    }

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
}
