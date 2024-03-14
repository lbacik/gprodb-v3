<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function getProjects(int $page = 1, int $limit = 10): array
    {
        return $this->projectRepository->findBy([], [], $limit, ($page - 1) * $limit);
    }

    public function getProject(string $projectId): Project|null
    {
        return $this->projectRepository->find($projectId);
    }

    public function count(): int
    {
        return $this->projectRepository->count([]);
    }
}
