<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use App\Entity\Project;
use App\Entity\User;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;
use JsonHub\SDK\Entity;
use JsonHub\SDK\EntityCollection;
use JsonHub\SDK\FilterCriteria;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

class JSONHubService
{
    private Client $client;

    public function __construct(
        string $jsonHubApiUrl,
        LoggerInterface $logger,
        HttpClient $httpClient,
        private readonly Security $security,
    ) {
        $this->client = ClientFactory::create($jsonHubApiUrl, $logger, $httpClient);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function lastQueryCount(): int
    {
        return $this->client->getLastQueryCount();
    }

    public function save(Entity $entity): Entity
    {
        if ($entity->id === null) {
            return $this->client->createEntity($entity, $this->getToken());
        }

        return $this->client->updateEntity($entity, $this->getToken());
    }

    public function delete(string $entityId): void
    {
        $this->client->deleteEntity($entityId, $this->getToken());
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

    public function findById(string $id): Entity|null
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $token = $user?->getJsonHubAccessToken();

        try {
            return $this->client->getEntity($id, $token);
        } catch (RuntimeException $exception) {
            return null;
        }
    }

    public function find(FilterCriteria $criteria): EntityCollection
    {
        return $this->client
            ->getEntities($criteria);
    }

    public function findMy(FilterCriteria $criteria): EntityCollection
    {
        return $this->client
            ->getMyEntities($criteria, $this->getToken());
    }

    private function getToken(): string
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if ($user === null) {
            throw new RuntimeException('No user');
        }

        return $user->getJsonHubAccessToken();
    }
}
