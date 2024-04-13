<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Project;
use JsonHub\SDK\Client;
use JsonHub\SDK\ClientFactory;

class JSONHubService
{
    private Client $client;

    public function __construct(string $jsonHubApiUrl)
    {
        $this->client = ClientFactory::create($jsonHubApiUrl);
    }

    public function getProject(string $id): Project|null
    {
        $data = $this->client->getEntity($id);

        if ($data === null) {
            return null;
        }

        $projectData = $data['data'];

        return new Project(
            $projectData['id'],
            $projectData['name'],
            $projectData['description'],
            $projectData['url'],
            $projectData['image'],
            $projectData['tags'],
            $projectData['created_at'],
            $projectData['updated_at']
        );
    }
}
