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
            ->setNewsletter(match($entity->data['newsletter'] ?? null) {
                'MailingR' => MailingProviderEnum::MAILINGR,
                default => MailingProviderEnum::GENERIC,
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
                'newsletter' => match($mailingProvider->getNewsletter()) {
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
