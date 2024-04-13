<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\LandingPage;
use App\Repository\LandingPageRepositoryInterface;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function __construct()
    {
    }

    public function save(LandingPage $landingPage): void
    {
    }
}
