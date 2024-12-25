<?php

declare(strict_types=1);

namespace App\Type;

use App\Entity\Project;
use Sushi\ObjectCollection;

class ProjectCollection extends ObjectCollection
{
    protected static string $type = Project::class;
}
