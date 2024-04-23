<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailingProvider;

interface MailingProviderRepositoryInterface
{
    public function findByLandingPageId(string $landingPageId): MailingProvider|null;
    public function save(MailingProvider $mailingProvider): void;
}
