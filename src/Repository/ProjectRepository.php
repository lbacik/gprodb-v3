<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;
use JsonHub\SDK\EntityCollection;
use JsonHub\SDK\FilterCriteria;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ProjectRepository
{
    private Client $jsonHubClient;

    public function __construct(
        #[Autowire(env: 'JSON_HUB_API_URL')]
        string $jsonHubApiUrl,
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
}
