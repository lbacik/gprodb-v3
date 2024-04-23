<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MailingRConfig;

interface MailingRConfigRepositoryInterface
{
    public function findByLandingPageId(string $landingPageId): MailingRConfig|null;
    public function save(MailingRConfig $mailingRConfig): void;
}
