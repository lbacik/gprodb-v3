<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

//use Doctrine\ORM\Mapping as ORM;

//#[ORM\Entity(repositoryClass: LinkRepository::class)]
//#[ORM\Table(name: 'links')]
class Link
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\ManyToOne(inversedBy: 'links')]
//    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

//    #[ORM\Column(length: 64, nullable: true)]
    private string|null $name = null;

//    #[ORM\Column(length: 255)]
    #[Assert\Url]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    private string $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

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
