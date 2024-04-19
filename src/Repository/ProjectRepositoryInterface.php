<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Type\ProjectCollection;

interface ProjectRepositoryInterface
{
    public function find(string $id): Project|null;
    public function findBySearchString(string $searchString, int $offset, int $limit): ProjectCollection;
    public function countBySearchString(string $searchString): int;
    public function save(Project $project): void;
}
