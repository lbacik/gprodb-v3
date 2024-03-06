<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ProjectService
{
    public function __construct(
        #[Autowire(env: 'PROJECT_INFO_DEFINITION' )]
        private readonly string $projectInfoDefinitionId,
    ) {
    }

    public function getProjects(): array
    {
        dump($this->projectInfoDefinitionId);

        return [];
    }
}
