<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LandingPage;

interface LandingPageRepositoryInterface
{
    public function findByProjectId(string $projectId): LandingPage|null;
    public function save(string $projectId, LandingPage $landingPage): void;
}
