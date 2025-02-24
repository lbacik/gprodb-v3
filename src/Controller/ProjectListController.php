<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\NewProjectType;
use App\Infrastructure\JsonHub\Exception\AuthenticationException;
use App\Service\ProjectService;
use App\Type\ProjectCollection;
use JsonHub\SDK\Exception\UnauthorizedException;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ProjectListController extends BaseAbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        Security $security,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
    ) {
        parent::__construct(
            $security,
            $requestStack,
            $tokenStorage,
        );
    }

    #[Route('/projects', name: 'app_project_list', methods: ['GET'])]
    public function index(
        #[MapQueryParameter()] string $q = '',
        #[MapQueryParameter()] int $page = 1,
        #[MapQueryParameter()] int $limit = 10,
    ): Response {
        $callback = fn (): ProjectCollection => $this->projectService->getProjects($q, $page, $limit);

        $result = $this->executeTokenSensitiveAction($callback);
        if ($result instanceof RedirectResponse) {
            return $result;
        }

        $projects = $result;
        $projectsCount = $this->projectService->count($q);

        $adapter = new FixedAdapter($projectsCount, (array) $projects);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        return $this->render('project_list/index.html.twig', [
            'projects' => $pagerfanta,
            'searchString' => $q,
        ]);
    }
}
