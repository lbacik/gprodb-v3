<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Criteria;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function getProjects(string $searchString, int $page = 1, int $limit = 10): array
    {
        return $this->projectRepository->findBySearchString($searchString, ($page - 1) * $limit, $limit);
    }

    public function getProject(string $projectId): Project|null
    {
        return $this->projectRepository->find($projectId);
    }

    public function count(string $searchString): int
    {
        return $this->projectRepository->countBySearchString($searchString);
    }
}
