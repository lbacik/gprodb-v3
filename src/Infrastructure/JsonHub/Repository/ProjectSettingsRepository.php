<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\ProjectSettings;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\ProjectSettingsRepositoryInterface;

class ProjectSettingsRepository implements ProjectSettingsRepositoryInterface
{
    public function __construct(
    ) {
    }

    public function save(ProjectSettings $settings): void
    {
    }

    public function findByProjectId(string $projectId): ProjectSettings
    {
        $settings = new ProjectSettings();
        return $settings;
    }
}
