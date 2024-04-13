<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\Project;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\ProjectRepositoryInterface;
use JsonHub\SDK\FilterCriteria;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private readonly JSONHubService $jsonHubService,
        private readonly string $projectDefinitionUuid,
    ) {
    }

    /**
     * @return Project[] Returns an array of Project objects
     */
    public function findBySearchString(string $searchString, int $offset, int $limit): array
    {
        $entities = $this->jsonHubService->find(
            new FilterCriteria(
                page:           1,
                limit:          $limit,
                definitionUuid: $this->projectDefinitionUuid,
                dataSearchTerm: $searchString,
            )
        );



        return [];
    }

    public function countBySearchString(string $searchString): int
    {
        return 0;
    }

    public function save(Project $project): void
    {

    }

    public function find(string $id): Project|null
    {
        $entity = $this->jsonHubService->findById($id);

        return null;
    }
}
