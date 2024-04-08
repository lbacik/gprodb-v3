<?php

namespace App\Entity;

use App\Repository\MailingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailingRepository::class)]
class Mailing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $type = null;

    #[ORM\Column(length: 32)]
    private ?string $provider = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $config = null;

    #[ORM\ManyToOne(inversedBy: 'mailing')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectSettings $projectSettings = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getConfig(): array
    {
        return json_decode($this->config, true);
    }

    public function setConfig(array $config): static
    {
        $this->config = json_encode($config);

        return $this;
    }

    public function getProjectSettings(): ?ProjectSettings
    {
        return $this->projectSettings;
    }

    public function setProjectSettings(?ProjectSettings $projectSettings): static
    {
        $this->projectSettings = $projectSettings;

        return $this;
    }
}
