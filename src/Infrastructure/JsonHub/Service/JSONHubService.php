<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use App\Entity\Project;
use App\Entity\User;
use App\Infrastructure\JsonHub\Exception\AuthenticationException;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;
use JsonHub\SDK\Entity;
use JsonHub\SDK\EntityCollection;
use JsonHub\SDK\Exception\UnauthorizedException;
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
            $callback = fn(string|null $token): Entity => $this->client->createEntity($entity, $token);
        } else {
            $callback = fn(string|null $token): Entity => $this->client->updateEntity($entity, $token);
        }

        return $this->tokenWrapper($callback, isUserRequired: true);
    }

    public function delete(string $entityId): void
    {
        $this->tokenWrapper(
            fn (string|null $token) => $this->client->deleteEntity($entityId, $token),
            isUserRequired: true
        );
    }

    public function findById(string $id): Entity|null
    {
        try {
            return $this->tokenWrapper(
                fn(string|null $token): Entity => $this->client->getEntity($id, $token)
            );
        } catch (AuthenticationException $exception) {
            throw $exception;
        } catch (RuntimeException $exception) {
            return null;
        }
    }

    public function find(FilterCriteria $criteria): EntityCollection
    {
        return $this->tokenWrapper(
            fn (string|null $token): EntityCollection => $this->client->getEntities($criteria, $token)
        );
    }

    public function findMy(FilterCriteria $criteria): EntityCollection
    {
        return $this->tokenWrapper(
            fn (string|null $token): EntityCollection => $this->client->getEntities(
                $criteria,
                $this->getToken()
            )
        );
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

    private function tokenWrapper($callback, bool $isUserRequired = false): mixed
    {
        $retry = 0;
        while (true) {
            try {
                /** @var User $user */
                $user = $this->security->getUser();
                if ($isUserRequired && !$user) {
                    throw new RuntimeException('No user');
                }

                $token = $user?->getJsonHubAccessToken();

                return $callback($token);
            } catch (UnauthorizedException $exception) {
                if ($retry++ >= 1) {
                    break;
                }

                $this->refreshUserToken($user);
            }
        }

        throw new RuntimeException('Something went wrong');
    }

    private function refreshUserToken($user): void
    {
        throw AuthenticationException::tokenExpired();
    }
}
