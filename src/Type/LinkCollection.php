<?php

declare(strict_types=1);

namespace App\Type;

use Sushi\ObjectCollection;

class LinkCollection extends ObjectCollection
{
    protected static string $type = Link::class;

    public static function fromArray(array $data): static
    {
        return new static(
            array_map(
                fn(array $link) => Link::fromArray($link),
                $data
            )
        );
    }
}
