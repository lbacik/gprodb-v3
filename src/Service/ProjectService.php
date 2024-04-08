<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\LandingPage;
use App\Entity\Project;
use App\Entity\ProjectSettings;
use App\Entity\User;
use App\Repository\LandingPageRepository;
use App\Repository\ProjectRepository;
use App\Repository\ProjectSettingsRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly LandingPageRepository $landingPageRepository,
        private readonly ProjectSettingsRepository $projectSettingsRepository,
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

    public function createWithName(string $name): int
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
            foreach($updates->getLinks() as $link) {
                $link->setProject($project);
            }
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
}
