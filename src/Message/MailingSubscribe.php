<?php

declare(strict_types=1);

namespace App\Message;

readonly class MailingSubscribe
{
    public function __construct(
        public string $email,
        public string $entityId,
    ) {
    }
}
