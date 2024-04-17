<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use GProDB\LandingPage\Builder;
use GProDB\LandingPage\ElementBuildsDirector;
use GProDB\LandingPage\LandingPage;
use GProDB\LandingPage\MapperFactory;
use GProDB\LandingPage\Mappers\LandingPageMapperEnum;
use JsonHub\SDK\Entity;
use JsonHub\SDK\FilterCriteria;

class LandingPageService
{
    public function __construct(
        private readonly JSONHubService $jsonHubService,
        private readonly string $landingPageDefinitionUuid,
        private readonly MapperFactory $mapperFactory,
    ) {
    }

    public function createBuilder(): Builder
    {
        return new Builder(
            new ElementBuildsDirector([])
        );
    }

    public function mapArrayToValues(array $json): LandingPage
    {
        $mapper = $this->mapperFactory->createArrayToLandingPage();

        return $mapper->map($json, LandingPageMapperEnum::PROJECT_V3);
    }

    public function mapValuesToArray(LandingPage $landingPage): array
    {
        $mapper = $this->mapperFactory->createLandingPageToArray();

        return $mapper->map($landingPage, LandingPageMapperEnum::PROJECT_V3);
    }

    public function getLandingPageEntity(string $projectId): Entity|null
    {
        if (empty($this->landingPageDefinitionUuid)) {
            return null;
        }

        $entities = $this->jsonHubService->getClient()->getEntities(
            new FilterCriteria(
                definitionUuid: $this->landingPageDefinitionUuid,
                parentUuid:     $projectId,
            )
        );

        if ($entities->count() === 0) {
            return null;
        }

        return $entities[0];
    }

    public function upsert(string $projectId, array $data): void
    {
        $landingPageEntity = $this->getLandingPageEntity($projectId);

        if ($landingPageEntity === null) {
            $entity = new Entity(
                id: null,
                iri: null,
                slug: null,
                data: $data,
                definition: $this->landingPageDefinitionUuid,
                parent: $projectId,
            );
        } else {
            $entity = new Entity(
                id: $landingPageEntity->id,
                iri: $landingPageEntity->iri,
                slug: $landingPageEntity->slug,
                data: $data,
                definition: $this->landingPageDefinitionUuid,
                parent: $projectId,
            );
        }

        $this->jsonHubService->save($entity);
    }
}
