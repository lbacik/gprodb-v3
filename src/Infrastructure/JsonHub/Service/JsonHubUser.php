<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use JsonHub\SDK\ClientRequest;
use JsonHub\SDK\ClientRequestFactory;
use JsonHub\SDK\Request\ForgotPasswordResetRequest;
use JsonHub\SDK\Request\SendForgotPasswordRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JsonHubUser
{
    private ClientRequest $client;

    public function __construct(
        string $jsonHubUrl,
        private readonly UrlGeneratorInterface $router,
    ) {
        $this->client = ClientRequestFactory::create($jsonHubUrl);
    }

    public function sendForgotPassword(string $email): void
    {
        $this->client->request(SendForgotPasswordRequest::class, [
            SendForgotPasswordRequest::EMAIL => $email,
            SendForgotPasswordRequest::RESET_PASSWORD_LINK => $this->router->generate('app_forgot_password_form'),
        ]);
    }

    public function forgotPassword(string $token, string $newPassword): void
    {
        $this->client->request(ForgotPasswordResetRequest::class, [
            ForgotPasswordResetRequest::PASSWORD => $newPassword,
            ForgotPasswordResetRequest::TOKEN => $token,
        ]);
    }
}
