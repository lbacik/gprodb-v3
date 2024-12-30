<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Service;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClient implements ClientInterface
{

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->httpClient->request(
            $request->getMethod(),
            (string) $request->getUri(),
            [
                'headers' => $request->getHeaders(),
                'body' => $request->getBody()->getContents(),
            ]
        );

        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(throw: false),
            $response->getContent(throw: false),
        );
    }
}
