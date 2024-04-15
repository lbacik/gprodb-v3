<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mailing;
use App\Entity\Project;
use App\Entity\ProjectSettings;
use App\Entity\User;
use App\Repository\LandingPageRepositoryInterface;
use App\Repository\MailingRepositoryInterface;
use App\Repository\ProjectRepositoryInterface;
use App\Repository\ProjectSettingsRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $projectRepository,
        private readonly LandingPageRepositoryInterface $landingPageRepository,
        private readonly ProjectSettingsRepositoryInterface $projectSettingsRepository,
        private readonly MailingRepositoryInterface $mailingRepository,
        private readonly Security $security,
    ) {
    }

    public function getProjects(string $searchString, int $page = 1, int $limit = 10): array
    {
        return $this->projectRepository->findBySearchString($searchString, ($page - 1) * $limit, $limit);
    }

    public function getProject(string $projectId): Project|null
    {
        return $this->projectRepository->find($projectId);
    }

    public function count(string $searchString): int
    {
        return $this->projectRepository->countBySearchString($searchString);
    }

    public function getProjectSettings(Project $project): ProjectSettings
    {
        $user = $this->security->getUser();

        if ($project->getOwner() !== $user) {
            throw new \LogicException('You are not allowed to access this page');
        }

        return $project->getSettings();
    }

    public function updateLandingPageDomain(Project $project, string|null $domain): void
    {
        $user = $this->security->getUser();

        if ($project->getOwner() !== $user) {
            throw new \LogicException('You are not allowed to access this page');
        }

        $settings = $project->getSettings();
        $settings->setDomain($domain);
        $this->projectSettingsRepository->save($settings);
    }

    public function createWithName(string $name): string
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if ($user === null) {
            throw new \LogicException('User must be authenticated to create a project');
        }

        $project = new Project();
        $project->setName($name);
        $project->setOwner($user);
        $this->projectRepository->save($project);

        return $project->getId();
    }

    public function updateProject(string $id, Project $updates, bool $updateLinks = false): void
    {
       /** @var User $user */
        $user = $this->security->getUser();
        if ($user === null) {
            throw new \LogicException('User must be authenticated to create a project');
        }

        $project = $this->projectRepository->find($id);

        if ($project === null) {
            throw new \LogicException('Project not found');
        }

        if ($project->getOwner() !== $user) {
            throw new \LogicException('User is not the owner of the project');
        }

        if ($updateLinks) {
            $project->setLinks($updates->getLinks());
        } else {
            $project->setName($updates->getName());
            $project->setDescription($updates->getDescription());
        }

        $this->projectRepository->save($project);
    }

    public function saveLandingPage(int $projectId, array $data): void
    {
        $project = $this->projectRepository->find($projectId);

        if ($project === null) {
            throw new \LogicException('Project not found');
        }

        $landingPage = $data['base'];
        $landingPage->setHero($data['hero']);
        $landingPage->setAbout($data['about']);

        $this->landingPageRepository->save($landingPage);

        $settings = $project->getSettings();
        if ($settings === null) {
            $settings = new ProjectSettings();
            $this->projectSettingsRepository->save($settings);
            $project->setSettings($settings);
            $this->projectRepository->save($project);
        }

        if ($settings->getLandingPage() === null) {
            $settings->setLandingPage($landingPage);
            $this->projectSettingsRepository->save($settings);
        }
    }

    public function setNewsletter(Project $project, string $provider, array $data): void
    {
        $user = $this->security->getUser();

        if ($project->getOwner() !== $user) {
            throw new \LogicException('You are not allowed to access this page');
        }

        $settings = $project->getSettings();
        $newsletter = $settings->getNewsletter();

        if ($newsletter === null) {
            $newsletter = new Mailing('newsletter');
        }

        $newsletter->setProvider($provider);
        $newsletter->setConfig($data);

        if ($newsletter->getProjectSettings() === null) {
            $newsletter->setProjectSettings($settings);
        }

        $this->mailingRepository->save($newsletter);
    }
}
