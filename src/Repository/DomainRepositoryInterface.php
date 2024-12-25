<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Domain;
use App\Entity\LandingPage;

interface DomainRepositoryInterface
{
    public function findByLandingPageId(string $landingPageId): Domain|null;
    public function save(Domain $domain, LandingPage $landingPage): void;
    public function delete(Domain $domain): void;
}
