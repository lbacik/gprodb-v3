<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\ProjectSettings;
use App\Repository\ProjectSettingsRepositoryInterface;

class ProjectSettingsRepository implements ProjectSettingsRepositoryInterface
{
    public function __construct()
    {
    }

    public function save(ProjectSettings $settings): void
    {
    }
}
