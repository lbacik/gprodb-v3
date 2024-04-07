<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectSettingsRepository::class)]
class ProjectSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $landingPageEntityId = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $customDomainEntityId = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $newsletterProviderEntityId = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $newsletterProviderConfigEntityId = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?LandingPage $landingPage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLandingPageEntityId(): ?string
    {
        return $this->landingPageEntityId;
    }

    public function setLandingPageEntityId(?string $landingPageEntityId): static
    {
        $this->landingPageEntityId = $landingPageEntityId;

        return $this;
    }

    public function getCustomDomainEntityId(): ?string
    {
        return $this->customDomainEntityId;
    }

    public function setCustomDomainEntityId(?string $customDomainEntityId): static
    {
        $this->customDomainEntityId = $customDomainEntityId;

        return $this;
    }

    public function getNewsletterProviderEntityId(): ?string
    {
        return $this->newsletterProviderEntityId;
    }

    public function setNewsletterProviderEntityId(?string $newsletterProviderEntityId): static
    {
        $this->newsletterProviderEntityId = $newsletterProviderEntityId;

        return $this;
    }

    public function getNewsletterProviderConfigEntityId(): ?string
    {
        return $this->newsletterProviderConfigEntityId;
    }

    public function setNewsletterProviderConfigEntityId(?string $newsletterProviderConfigEntityId): static
    {
        $this->newsletterProviderConfigEntityId = $newsletterProviderConfigEntityId;

        return $this;
    }

    public function getLandingPage(): ?LandingPage
    {
        return $this->landingPage;
    }

    public function setLandingPage(?LandingPage $landingPage): static
    {
        $this->landingPage = $landingPage;

        return $this;
    }
}
