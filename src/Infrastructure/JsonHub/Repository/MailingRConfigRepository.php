<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\MailingRConfig;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\MailingRConfigRepositoryInterface;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;

class MailingRConfigRepository implements MailingRConfigRepositoryInterface
{
    private const API_KEY = 'api_key';
    private const PRODUCT_ID = 'product_id';

    public function __construct(
        private readonly string $mailingRConfigDefinitionUuid,
        private readonly JSONHubService $jsonHubService,
    ) {
    }

    public function findByLandingPageId(string $landingPageId): MailingRConfig|null
    {
        $entities = $this->jsonHubService->findMy(
            new FilterCriteria(
                definitionUuid: $this->mailingRConfigDefinitionUuid,
                parentUuid: $landingPageId,
            ),
        );

        if ($entities->count() === 0) {
            return null;
        }

        $entity = $entities[0];

        return (new MailingRConfig($entity->id))
            ->setApiKey($entity->data[self::API_KEY] ?? '')
            ->setProductId($entity->data[self::PRODUCT_ID] ?? '')
            ->setParent($entity->parent);
    }

    public function save(MailingRConfig $mailingRConfig): void
    {
        $entity = new Entity(
            id: $mailingRConfig->getId(),
            iri: null,
            slug: null,
            data: [
                self::API_KEY => $mailingRConfig->getApiKey(),
                self::PRODUCT_ID => $mailingRConfig->getProductId(),
            ],
            definition: $this->mailingRConfigDefinitionUuid,
            parent: $mailingRConfig->getParent(),
            private: true,
        );

        $this->jsonHubService->save($entity);
    }
}
