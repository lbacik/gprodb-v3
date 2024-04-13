<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mailing;

interface MailingRepositoryInterface
{
    public function save(Mailing $mailing): void;
}
