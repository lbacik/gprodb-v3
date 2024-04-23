<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\LandingPage;
use App\Infrastructure\JsonHub\Service\LandingPageService;
use App\Repository\LandingPageRepositoryInterface;
use App\Type\LandingPageAbout;
use App\Type\LandingPageHero;
use Exception;
use GProDB\LandingPage\ElementName;
use GProDB\LandingPage\Elements\About;
use GProDB\LandingPage\Elements\Contact;
use GProDB\LandingPage\Elements\Hero;
use GProDB\LandingPage\Elements\Meta;
use GProDB\LandingPage\Elements\Newsletter;
use GProDB\LandingPage\LandingPage as LandingPageValues;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function __construct(
        private readonly LandingPageService $landingPageService,
//        private readonly JSONHubService $jsonHubService,
    ) {
    }

    public function save(string $projectId, LandingPage $landingPage): void
    {
        $values = (new LandingPageValues())
            ->addElement(new Meta(
                $landingPage->getName(),
                $landingPage->getTitle(),
            ))
            ->addElement(new Hero(
                $landingPage->getHero()?->getTitle(),
                $landingPage->getHero()?->getSubtitle() ?? '',
            ))
            ->addElement(new About(
                $landingPage->getAbout()->getSubtitle() ?? '',
                $landingPage->getAbout()->getColumnLeft() ?? '',
                $landingPage->getAbout()->getColumnRight() ?? '',
            ))
            ->addElement(new Contact(
                $landingPage->getContactInfo() ?? '',
                $landingPage->getContact(),
            ))
            ->addElement(new Newsletter(
                $landingPage->getNewsletterInfo() ?? '',
                $landingPage->getNewsletter(),
            ));

        $this->landingPageService->upsert(
            $projectId,
            $this->landingPageService->mapValuesToArray($values)
        );
    }

    public function findByProjectId(string $projectId): LandingPage|null
    {
        $entity = $this->landingPageService->getLandingPageEntity($projectId);

        if ($entity === null) {
            return null;
        }

        $values = $this->landingPageService->mapArrayToValues($entity->data);

        $landingPage = new LandingPage($entity->id);

        foreach($values->getElements() as $element) {
            match($element->elementName()) {
                ElementName::META => $landingPage
                    ->setName($element->name)
                    ->setTitle($element->title),
                ElementName::HERO => $this->addHero($element, $landingPage),
                ElementName::ABOUT => $this->addAbout($element, $landingPage),
                ElementName::CONTACT => $landingPage
                    ->setContactInfo($element->description)
                    ->setContact($element->enabled),
                ElementName::NEWSLETTER => $landingPage
                    ->setNewsletterInfo($element->description)
                    ->setNewsletter($element->enabled),
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

    private function addAbout(About $element, LandingPage $landingPage): void
    {
        $about = new LandingPageAbout();
        $about->setSubtitle($element->subtitle);
        $about->setColumnLeft($element->columnLeft);
        $about->setColumnRight($element->columnRight);
//        $about->setEnabled($element->enabled);
        $landingPage->setAbout($about);
    }
}
