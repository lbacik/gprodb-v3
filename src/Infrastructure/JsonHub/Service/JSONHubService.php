<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use App\Entity\Project;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;

class JSONHubService
{
    private Client $client;

    public function __construct(string $jsonHubApiUrl)
    {
        $this->client = ClientFactory::create($jsonHubApiUrl);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getProject(string $id): Project|null
    {
        $data = $this->client->getEntity($id);

        if ($data === null) {
            return null;
        }

        $projectData = $data['data'];

        return new Project();
    }

    public function findById(string $id): Entity
    {
        return $this->client->getEntity($id);
    }

    public function find(FilterCriteria $criteria): array // ProjectCollection
    {
//        $result = new ProjectCollection();
//        $entities = $this->jsonHubClient->getEntities($criteria);
//
//        foreach($entities as $entity) {
//            $result[] = Project::createFromJson($entity->id, $entity->data);
//        }
//
//        $result->setTotal($this->jsonHubClient->getLastQueryCount());
//
//        return $result;

        return [];
    }
}
