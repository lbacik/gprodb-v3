<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SendContactRequestService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $internalApiUrl,
        private readonly string $internalApiToken,
        private readonly string $emailSenderAddress,
    ) {
    }

    public function send(array $contact): void
    {
        $response = $this->httpClient->request(
            'POST',
            $this->internalApiUrl . '/messages',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->internalApiToken,
                ],
                'json' => [
                    'entityId' => $contact['entityId'],
                    'senderSystemAddress'=> $this->emailSenderAddress,
                    'senderName' => $contact['name'],
                    'senderEmail' => $contact['email'],
                    'subject' => $contact['subject'],
                    'message' => $contact['message'],
                ],
            ]
        );
    }
}
