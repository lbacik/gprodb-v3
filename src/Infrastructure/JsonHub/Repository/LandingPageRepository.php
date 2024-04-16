<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\LandingPage;
use App\Entity\LandingPageHero;
use App\Infrastructure\JsonHub\Service\LandingPageService;
use App\Repository\LandingPageRepositoryInterface;
use Exception;
use GProDB\LandingPage\ElementName;
use GProDB\LandingPage\Elements\Hero;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function __construct(
        private readonly LandingPageService $landingPageService,
    ) {
    }

    public function save(LandingPage $landingPage): void
    {
    }

    public function findByProjectId(string $projectId): LandingPage|null
    {
        $entity = $this->landingPageService->getLandingPageEntity($projectId);

        if ($entity === null) {
            return null;
        }

        $values = $this->landingPageService->map($entity->data);

        $landingPage = new LandingPage($entity->id);

        foreach($values->getElements() as $element) {
            match($element->elementName()) {
                ElementName::HERO => $this->addHero($element, $landingPage),
                default => throw new Exception('Unknown element type'),
            };
        }

        return $landingPage;
    }

    private function addHero(Hero $element, LandingPage $landingPage): void
    {
        $hero = new LandingPageHero();
        $hero->setTitle($element->title);
        $hero->setSubtitle($element->subtitle);
        $landingPage->setHero($hero);
    }
}
