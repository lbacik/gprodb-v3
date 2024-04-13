<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProjectSettings;

interface ProjectSettingsRepositoryInterface
{
    public function save(ProjectSettings $settings): void;
}
