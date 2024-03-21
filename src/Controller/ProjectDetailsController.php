<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectSettings;
use App\Form\ContactType;
use App\Form\LinksType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectDetailsController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly Security $security
    ) {
    }

    #[Route('/projects/{id}/{tab}', name: 'app_project_details', methods: ['GET'])]
    public function index(string $id, string|null $tab = 'about'): Response
    {
        $project = $this->projectService->getProject($id);

        if ($project === null) {
            throw $this->createNotFoundException('The project does not exist');
        }

        match($tab) {
            'links' => $form = $this->createForm(LinksType::class, $project),
            'contact' => $form = $this->createForm(ContactType::class),
            'settings' => $projectSettings = $this->getProjectSettings($project),
            default => $form = null,
        };

        return $this->render('project_details/index.html.twig', [
            'projectId' => $id,
            'tab' => $tab,
            'project' => $project,
            'projectSettings' => $projectSettings ?? null,
            'form' => $form ?? null,
        ]);
    }

    #[Route('/projects/{id}/contact', name: 'app_project_contact', methods: ['POST'])]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Your message has been sent');
            return new Response(null, 204);
        }

        return new Response(null, 400);
    }

    public function getProjectSettings(Project $project): ProjectSettings
    {
        $user = $this->security->getUser();

        if ($project->getOwner() !== $user) {
            throw $this->createAccessDeniedException('You are not allowed to access this page');
        }

        return $this->projectService->getProjectSettings($project);
    }
}
