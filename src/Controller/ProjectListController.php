<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectListController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects', name: 'app_project_list')]
    public function index(): Response
    {
        $projects = $this->projectService->getProjects();

        return $this->render('project_list/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
