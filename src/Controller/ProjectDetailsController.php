<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectDetailsController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects/{id}', name: 'app_project_details')]
    public function index(string $id): Response
    {
        $project = $this->projectService->getProject($id);

        if ($project === null) {
            throw $this->createNotFoundException('The project does not exist');
        }

        return $this->render('project_details/index.html.twig', [
            'project' => $project,
        ]);
    }
}
