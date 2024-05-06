<?php

declare(strict_types=1);

namespace App\Type;

enum MailingRoutingKey: string
{
    case GENERAL = 'general';
    case MAILING_R = 'mailingR';
}
