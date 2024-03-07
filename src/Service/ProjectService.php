<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectCollection;
use App\Repository\ProjectRepository;
use JsonHub\SDK\FilterCriteria;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ProjectService
{
    public function __construct(
        #[Autowire(env: 'PROJECT_INFO_DEFINITION' )]
        private readonly string $projectInfoDefinitionId,
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function getProjects(int $page = 1, int $limit = 10): ProjectCollection
    {
        return $this->projectRepository->find(
            new FilterCriteria(
                page: $page,
                limit: $limit,
                definitionUuid: $this->projectInfoDefinitionId,
            )
        );
    }

    public function getProject(string $projectId): Project|null
    {
        return $this->projectRepository->findById($projectId);
    }
}
