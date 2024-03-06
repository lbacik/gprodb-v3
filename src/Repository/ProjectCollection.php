<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Sushi\ObjectCollection;

class ProjectCollection extends ObjectCollection
{
    protected static string $type = Project::class;

    private int $total = 0;

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
