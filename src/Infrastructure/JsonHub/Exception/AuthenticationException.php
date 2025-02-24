<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Exception;

use RuntimeException;

class AuthenticationException extends RuntimeException
{
    public static function tokenExpired(): self
    {
        return new self();
    }
}
