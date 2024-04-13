<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Repository;

use App\Entity\Mailing;
use App\Repository\MailingRepositoryInterface;

class MailingRepository implements MailingRepositoryInterface
{
    public function __construct()
    {
    }

    public function save(Mailing $mailing): void
    {
    }
}
