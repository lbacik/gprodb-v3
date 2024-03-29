<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use App\Entity\ProjectSettings;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
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
        return new ProjectSettings();
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
}
