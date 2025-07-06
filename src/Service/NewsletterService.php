<?php

declare(strict_types=1);

namespace App\Service;

use App\Message\MailingSubscribe;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class NewsletterService
{
    public function __construct(
        private readonly string $gprodbEntityUuid,
        private readonly MessageBusInterface $messageBus,
        private readonly string $amqpNewsletterRoutingKey,
    ) {
    }

    public function subscribe(string $email): void
    {
        $this->messageBus->dispatch(
            new MailingSubscribe($email, $this->gprodbEntityUuid),
        );
    }
}
