<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class LandingPage
{
    private ?string $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $name = null;

    private ?string $title = null;

    private bool $contact = false;

    private ?string $contactInfo = null;

    private ?bool $newsletter = null;

    private ?string $newsletterInfo = null;

    private ?LandingPageHero $hero = null;

    private ?LandingPageAbout $about = null;

    public function __construct(
        string|null $id = null,
    ) {
        $this->id = $id;
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContact(): bool
    {
        return $this->contact;
    }

    public function setContact(bool $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContactInfo(): ?string
    {
        return $this->contactInfo;
    }

    public function setContactInfo(?string $contactInfo): static
    {
        $this->contactInfo = $contactInfo;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): static
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getNewsletterInfo(): ?string
    {
        return $this->newsletterInfo;
    }

    public function setNewsletterInfo(?string $newsletterInfo): static
    {
        $this->newsletterInfo = $newsletterInfo;

        return $this;
    }

    public function getHero(): ?LandingPageHero
    {
        return $this->hero;
    }

    public function setHero(?LandingPageHero $hero): static
    {
        $this->hero = $hero;

        return $this;
    }

    public function getAbout(): ?LandingPageAbout
    {
        return $this->about;
    }

    public function setAbout(?LandingPageAbout $about): static
    {
        $this->about = $about;

        return $this;
    }
}
