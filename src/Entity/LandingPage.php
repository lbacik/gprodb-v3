<?php

namespace App\Entity;

use App\Repository\LandingPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LandingPageRepository::class)]
class LandingPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column]
    private bool $contact = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contactInfo = null;

    #[ORM\Column]
    private ?bool $newsletter = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $newsletterInfo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?LandingPageHero $hero = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?LandingPageAbout $about = null;

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

    public function isNewsletter(): ?bool
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
