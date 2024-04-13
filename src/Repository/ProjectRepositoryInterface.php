<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;

interface ProjectRepositoryInterface
{
    public function find(string $id): Project|null;

    /** @return Project[] Returns an array of Project objects */
    public function findBySearchString(string $searchString, int $offset, int $limit): array;
    public function countBySearchString(string $searchString): int;
    public function save(Project $project): void;
}
