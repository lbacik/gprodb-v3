<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectSettingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;

//#[ORM\Entity(repositoryClass: ProjectSettingsRepository::class)]
class ProjectSettings
{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\Column(length: 64, nullable: true)]
    private ?string $landingPageEntityId = null;

//    #[ORM\Column(length: 64, nullable: true)]
    private ?string $customDomainEntityId = null;

//    #[ORM\Column(length: 64, nullable: true)]
    private ?string $newsletterProviderEntityId = null;

//    #[ORM\Column(length: 64, nullable: true)]
    private ?string $newsletterProviderConfigEntityId = null;

//    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?LandingPage $landingPage = null;

//    #[ORM\Column(length: 128, nullable: true)]
    private ?string $domain = null;

//    #[ORM\OneToMany(targetEntity: Mailing::class, mappedBy: 'projectSettings', orphanRemoval: true)]
    private Collection $mailing;

    public function __construct()
    {
        $this->mailing = new ArrayCollection();
    }

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
