<?php

declare(strict_types=1);

namespace App\Type;

enum MailingProvider: string
{
    case GENERIC = 'generic';
    case MAILINGR = 'mailingr';

    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}
