<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\MailingProvider;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\MailingProviderRepositoryInterface;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;
use App\Type\MailingProvider as MailingProviderEnum;

class MailingProviderRepository implements MailingProviderRepositoryInterface
{
    public function __construct(
        private readonly string $mailingProviderDefinitionUuid,
        private readonly JSONHubService $jsonHubService,
    ) {
    }

    public function findByLandingPageId(string $landingPageId): MailingProvider|null
    {
        $entities = $this->jsonHubService->find(
            new FilterCriteria(
                definitionUuid: $this->mailingProviderDefinitionUuid,
                parentUuid: $landingPageId,
            ),
        );

        if ($entities->count() === 0) {
            return null;
        }

        $entity = $entities[0];

        return (new MailingProvider($entity->id))
            ->setName(match($entity->data['name']) {
                MailingProviderEnum::GENERIC->value => MailingProviderEnum::GENERIC,
                'MailingR' => MailingProviderEnum::MAILINGR,
            })
            ->setParent($entity->parent);
    }

    public function save(MailingProvider $mailingProvider): void
    {
        $entity = new Entity(
            id: $mailingProvider->getId(),
            iri: null,
            slug: null,
            data: [
                'name' => match($mailingProvider->getName()) {
                    MailingProviderEnum::GENERIC => MailingProviderEnum::GENERIC->value,
                    MailingProviderEnum::MAILINGR => 'MailingR',
                },
            ],
            definition: $this->mailingProviderDefinitionUuid,
            parent: $mailingProvider->getParent(),
        );

        $this->jsonHubService->save($entity);
    }
}
