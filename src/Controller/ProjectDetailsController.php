<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectSettings;
use App\Form\ContactType;
use App\Form\LinksType;
use App\Form\ProjectAboutType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ProjectDetailsController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
    ) {
    }

    #[Route('/projects/{id}/{tab}', name: 'app_project_details', methods: ['GET'])]
    public function index(
        string $id,
        string|null $tab = 'about',
        #[MapQueryParameter('edit')] bool $edit = false,
    ): Response {
        $project = $this->projectService->getProject($id);

        if ($project === null) {
            throw $this->createNotFoundException('The project does not exist');
        }

        return $this->render(
            'project_details/index.html.twig',
            array_merge(
                [
                    'projectId' => $id,
                    'tab' => $tab,
                    'project' => $project,
                    'form' => $this->getForm($tab, $edit, $project),
                    'template' => $this->getTemplateName($tab, $edit),
                ],
                $this->getAdditionalData($tab, $project)
            )
        );
    }

    #[Route('/projects/{id}/about', name: 'app_project_edit', methods: ['POST'])]
    public function projectEdit(Request $request, string $id): Response
    {
        $form = $this->createForm(ProjectAboutType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->projectService->updateProject($id, $form->getData());
            $this->addFlash('success', 'Your changes have been saved');

            return $this->redirectToRoute('app_project_details', ['id' => $id]);
        }

        return $this->render('project_details/index.html.twig', [
            'projectId' => $id,
            'tab' => 'about',
            'project' => $this->projectService->getProject($id),
            'form' => $form,
            'template' => $this->getTemplateName('about', true),
        ]);
    }

    #[Route('/projects/{id}/links', name: 'app_project_links_edit', methods: ['POST'])]
    public function linksEdit(Request $request, string $id): Response
    {
        $form = $this->createForm(LinksType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->updateProject($id, $form->getData(), true);
            $this->addFlash('success', 'Your changes have been saved');

            return $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'links']);
        }

        return $this->render('project_details/index.html.twig', [
            'projectId' => $id,
            'tab' => 'links',
            'project' => $this->projectService->getProject($id),
            'form' => $form,
            'template' => $this->getTemplateName('links', true),
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
        try {
            return $this->projectService->getProjectSettings($project);
        } catch (\LogicException $e) {
            throw $this->createAccessDeniedException($e->getMessage());
        }
    }

    private function getForm(string $tab, bool $edit, Project $project): FormInterface|null
    {
        return match($tab) {
            'about' => $edit ? $this->createForm(ProjectAboutType::class, $project) : null,
            'links' => $this->createForm(LinksType::class, $project),
            'contact' => $this->createForm(ContactType::class),
            default => null,
        };
    }

    private function getTemplateName(string $tab, bool $edit): string
    {
        return match($tab) {
            'about' => $edit ? '_about-edit' : '_about',
            'links' => $edit ? '_links-edit' : '_links',
            'contact' => '_contact',
            'settings' => '_settings',
            default => '_about',
        };
    }

    private function getAdditionalData(string $tab, Project $project): array
    {
        return match($tab) {
            'settings' => [
                'projectSettings' => $this->getProjectSettings($project),
            ],
            default => [],
        };
    }
}
