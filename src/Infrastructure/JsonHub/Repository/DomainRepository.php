<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\Domain;
use App\Entity\LandingPage;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use App\Repository\DomainRepositoryInterface;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;

class DomainRepository implements DomainRepositoryInterface
{
    public function __construct(
        private readonly string $domainDefinitionUuid,
        private readonly string $proxyDefaultTargetPrefix,
        private readonly JSONHubService $jsonHubService,
    ) {
    }

    public function findByLandingPageId(string $landingPageId): Domain|null
    {
        $entities = $this->jsonHubService->find(
            new FilterCriteria(
                definitionUuid: $this->domainDefinitionUuid,
                parentUuid: $landingPageId,
            )
        );

        if (count($entities) === 0) {
            return null;
        }

        $domain = $entities[0];

        return(new Domain($domain->id))
            ->setDomain($domain->data['domain']);
    }

    public function save(Domain $domain, LandingPage $landingPage): void
    {
        $entity = new Entity(
            id:         $domain->getId(),
            iri:        null,
            slug:       null,
            data:       [
                'domain' => $domain->getDomain(),
                'enable' => true,
                'target' => sprintf("%s%s", $this->proxyDefaultTargetPrefix, $landingPage->getId()),
            ],
            definition: $this->domainDefinitionUuid,
            parent:     $landingPage->getId(),
        );

        $this->jsonHubService->save($entity);
    }

    public function delete(Domain $domain): void
    {
        $this->jsonHubService->delete($domain->getId());
    }
}
