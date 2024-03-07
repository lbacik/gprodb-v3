<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;
use JsonHub\SDK\FilterCriteria;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;

class ProjectRepository
{
    private Client $jsonHubClient;

    public function __construct(
        #[Autowire(env: 'JSON_HUB_API_URL')]
        string $jsonHubApiUrl,
        private readonly LoggerInterface $logger,
    ) {
        $this->jsonHubClient = ClientFactory::create($jsonHubApiUrl);
    }

    public function find(FilterCriteria $criteria): ProjectCollection
    {
        $result = new ProjectCollection();
        $entities = $this->jsonHubClient->getEntities($criteria);

        foreach($entities as $entity) {
            $result[] = Project::createFromJson($entity->id, $entity->data);
        }

        $result->setTotal($this->jsonHubClient->getLastQueryCount());

        return $result;
    }

    public function findById(string $projectId): Project|null
    {
        try {
            $entity = $this->jsonHubClient->getEntity($projectId);
        } catch (Throwable $exception) {
            $this->logger->error('Failed to get project', [
                'projectId' => $projectId,
                'exception' => $exception,
            ]);
            return null;
        }

        return Project::createFromJson($entity->id, $entity->data);
    }
}
