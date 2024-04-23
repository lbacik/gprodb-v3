<?php

declare(strict_types=1);

namespace App\Type;

use App\Entity\Domain;
use App\Entity\LandingPage;
use App\Entity\MailingProvider;
use App\Entity\MailingRConfig;

class ProjectSettings
{
    private LandingPage|null $landingPage = null;

    private Domain|null $domain = null;

    private MailingProvider|null $mailingProvider = null;

    private MailingRConfig|null $mailingRConfig = null;

    public function getLandingPage(): ?LandingPage
    {
        return $this->landingPage;
    }

    public function setLandingPage(?LandingPage $landingPage): static
    {
        $this->landingPage = $landingPage;
        return $this;
    }

    public function getDomain(): Domain|null
    {
        return $this->domain;
    }

    public function setDomain(Domain|null $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getMailingProvider(): MailingProvider|null
    {
        return $this->mailingProvider;
    }

    public function setMailingProvider(MailingProvider|null $mailingProvider): static
    {
        $this->mailingProvider = $mailingProvider;
        return $this;
    }

    public function getMailingRConfig(): MailingRConfig|null
    {
        return $this->mailingRConfig;
    }

    public function setMailingRConfig(MailingRConfig|null $mailingRConfig): static
    {
        $this->mailingRConfig = $mailingRConfig;
        return $this;
    }
}
