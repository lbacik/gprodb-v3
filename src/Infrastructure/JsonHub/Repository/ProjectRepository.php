<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\Link;
use App\Entity\Project;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\ProjectRepositoryInterface;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private readonly JSONHubService $jsonHubService,
        private readonly string $projectDefinitionUuid,
        private readonly Security $security,
    ) {
    }

    /**
     * @return Project[] Returns an array of Project objects
     */
    public function findBySearchString(string $searchString, int $offset, int $limit): array
    {
        $entities = $this->jsonHubService->find(
            new FilterCriteria(
                page: (int) ceil(($offset + 1) / $limit),
                limit: $limit,
                definitionUuid: $this->projectDefinitionUuid,
                dataSearchTerm: $searchString,
            )
        );

        return array_map(
            fn (Entity $entity) => (new Project())
                ->setId($entity->id)
                ->setName($entity->data['name'])
                ->setDescription($entity->data['description'] ?? null),
//                ->setLinks($entity->data['links'] ?? []),
            (array) $entities
        );
    }

    public function countBySearchString(string $searchString): int
    {
        return $this->jsonHubService->getClient()->getLastQueryCount();
    }

    public function save(Project $project): void
    {
        if ($project->getId() === null) {

            $projectArray = $project->toArray();
            $data = array_reduce(
                array_keys($projectArray),
                fn (array $carry, $item) => !empty($projectArray[$item]) ? $carry + [$item => $projectArray[$item]] : $carry,
                []
            );

            $entity = new Entity(
                id: $project->getId(),
                iri: null,
                slug: '',
                data: $data,
                definition: $this->projectDefinitionUuid,
            );
        } else {
            $currentEntity = $this->jsonHubService->findById($project->getId());

            $data = [
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'links' => array_map(
                    fn (Link $link) => [
                        'name' => $link->getName() ?? '',
                        'url' => $link->getUrl(),
                    ],
                    array_values($project->getLinks()),
                ),
            ];

            $entity = new Entity(
                id: $currentEntity->id,
                iri: null,
                slug: '',
                data: $data,
                definition: null,
            );

        }

        $result = $this->jsonHubService->save($entity);

        if ($project->getId() === null) {
            $project->setId($result->id);
        }
    }

    public function find(string $id): Project|null
    {
        $entity = $this->jsonHubService->findById($id);

        if ($entity === null || str_ends_with($entity->definition, $this->projectDefinitionUuid) === false) {
            return null;
        }

        $project = (new Project())
            ->setId($entity->id)
            ->setName($entity->data['name'])
            ->setDescription($entity->data['description'] ?? null)
            ->setLinks(
                array_map(
                    fn (array $link) => (new Link())
                        ->setName($link['name'])
                        ->setUrl($link['url']),
                $entity->data['links'] ?? [],
                )
            );

        if ($entity->owned === true) {
            $project->setOwner($this->security->getUser());
        }

        return $project;
    }
}
