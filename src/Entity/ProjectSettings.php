<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ProjectSettings
{
    private ?string $landingPageEntityId = null;

    private ?string $customDomainEntityId = null;

    private ?string $newsletterProviderEntityId = null;

    private ?string $newsletterProviderConfigEntityId = null;

    private ?LandingPage $landingPage = null;

    private ?string $domain = null;

    private Collection $mailing;

    public function __construct()
    {
        $this->mailing = new ArrayCollection();
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

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return Collection<int, Mailing>
     */
    public function getMailing(): Collection
    {
        return $this->mailing;
    }

    public function addMailing(Mailing $mailing): static
    {
        if (!$this->mailing->contains($mailing)) {
            $this->mailing->add($mailing);
            $mailing->setProjectSettings($this);
        }

        return $this;
    }

    public function removeMailing(Mailing $mailing): static
    {
        if ($this->mailing->removeElement($mailing)) {
            // set the owning side to null (unless already changed)
            if ($mailing->getProjectSettings() === $this) {
                $mailing->setProjectSettings(null);
            }
        }

        return $this;
    }

    public function getNewsletter(): Mailing|null
    {
        $result = $this->getMailing()
            ->filter(fn(Mailing $mailing) => $mailing->getType() === 'newsletter')
            ->first();

        return $result === false ? null : $result;
    }
}
