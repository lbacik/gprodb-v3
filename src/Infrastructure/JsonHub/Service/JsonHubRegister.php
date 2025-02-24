<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use JsonHub\SDK\ClientRequest;
use JsonHub\SDK\ClientRequestFactory;
use JsonHub\SDK\Request\ActivationRequest;
use JsonHub\SDK\Request\RegisterRequest;
use JsonHub\SDK\Request\ResendActivationRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JsonHubRegister
{
    private ClientRequest $client;

    public function __construct(
        string $jsonHubUrl,
        private readonly UrlGeneratorInterface $router,
    ) {
        $this->client = ClientRequestFactory::create($jsonHubUrl);
    }

    public function register(string $email, string $password): void
    {
        $this->client->request(RegisterRequest::class, [
            RegisterRequest::FIELD_EMAIL => $email,
            RegisterRequest::FIELD_PASSWORD => $password,
            RegisterRequest::FIELD_ACTIVATION_URL => $this->router->generate(
                'app_register_confirm',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }

    public function activate(string $token): void
    {
        $this->client->request(ActivationRequest::class, [
            ActivationRequest::TOKEN => $token,
        ]);
    }

    public function resend(string $email): void
    {
        $this->client->request(ResendActivationRequest::class, [
            ResendActivationRequest::EMAIL => $email,
        ]);
    }
}
