<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\LandingPage;

interface LandingPageRepositoryInterface
{
    public function save(LandingPage $landingPage): void;
}
