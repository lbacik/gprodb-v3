<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProjectService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProjectListController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects', name: 'app_project_list')]
    public function index(
        #[MapQueryParameter()] int $page = 1,
        #[MapQueryParameter()] int $limit = 10
    ): Response {

        $projects = $this->projectService->getProjects($page, $limit);
        $adapter = new FixedAdapter($projects->getTotal(), (array) $projects);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        return $this->render('project_list/index.html.twig', [
            'projects' => $pagerfanta,
        ]);
    }
}
