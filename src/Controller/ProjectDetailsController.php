<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Form\ContactType;
use App\Form\LinksType;
use App\Form\NewProjectType;
use App\Form\ProjectAboutType;
use App\Infrastructure\JsonHub\Exception\AuthenticationException;
use App\Service\ProjectService;
use App\Service\SendContactRequestService;
use App\Type\ProjectSettings;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProjectDetailsController extends BaseAbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly string $pagesUrlPrefix,
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

    #[Route('/projects/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_USER')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(NewProjectType::class, null, [
            'attr' => [
                'action' => $this->generateUrl('app_project_new'),
                'method' => 'POST',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            try {
                $projectId = $this->projectService->createWithName($project->getName());
                $this->addFlash('success', 'Project created');

                return $this->redirectToRoute(
                    'app_project_details',
                    [
                        'id' => $projectId,
                        'edit' => true,
                    ],
                    Response::HTTP_SEE_OTHER
                );
            } catch (AuthenticationException $exception) {
                return $this->reauthenticate();
            } catch (\Exception $e) {
                $form->addError(new FormError('Project could not be created'));
            }
        }

        return $this->render('project_details/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/projects/{id}/{tab}', name: 'app_project_details', methods: ['GET'])]
    public function index(
        string $id,
        string|null $tab = 'about',
        #[MapQueryParameter('edit')] bool $edit = false,
    ): Response {
        try {
            $project = $this->projectService->getProject($id);
            if ($project === null) {
                throw $this->createNotFoundException('The project does not exist');
            }
            $additionalData = $this->getAdditionalData($tab, $project);
        } catch (AuthenticationException $exception) {
            return $this->reauthenticate();
        }


        if ($edit) {
            $this->denyAccessUnlessGranted('ROLE_USER');

            if ($this->getUser() !== $project->getOwner()) {
                throw $this->createAccessDeniedException('You are not allowed to edit this project');
            }
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
                $additionalData,
            )
        );
    }

    #[Route('/projects/{id}/about', name: 'app_project_edit', methods: ['POST'])]
    #[isGranted('ROLE_USER')]
    public function projectEdit(Request $request, string $id): Response
    {
        $form = $this->createForm(ProjectAboutType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->projectService->updateProject($id, $form->getData());
            } catch (AuthenticationException $exception) {
                return $this->reauthenticate();
            }

            $this->addFlash('success', 'Your changes have been saved');

            return $this->redirectToRoute(
                'app_project_details',
                ['id' => $id],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->redirectToRoute('app_project_details', [
            'id' => $id,
            'tab' => 'about',
            'edit' => true,
        ]);
    }

    #[Route('/projects/{id}/links', name: 'app_project_links_edit', methods: ['POST'])]
    #[isGranted('ROLE_USER')]
    public function linksEdit(Request $request, string $id): Response
    {
        $form = $this->createForm(LinksType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->projectService->updateProject($id, $form->getData(), true);
            } catch (AuthenticationException $exception) {
                return $this->reauthenticate();
            }

            $this->addFlash('success', 'Your changes have been saved');

            return $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'links']);
        }

        return $this->redirectToRoute('app_project_details', [
            'id' => $id,
            'tab' => 'links',
            'edit' => true,
        ]);
    }

    #[Route('/projects/{id}/contact', name: 'app_project_contact', methods: ['POST'])]
    public function contact(
        Request $request,
        SendContactRequestService $contactRequestService,
    ): Response {
        $form = $this->createForm(ContactType::class, [
            'entityId' => $request->get('id'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRequestService->send($form->getData());

            $this->addFlash('success', 'Your message has been sent');
        }

        return $this->redirectToRoute('app_project_details', [
            'id' => $request->get('id'),
            'tab' => 'contact',
        ]);
    }

    public function getProjectSettings(Project $project): ProjectSettings
    {
        try {
            return $this->projectService->getProjectSettings($project);
        } catch (LogicException $e) {
            throw $this->createAccessDeniedException($e->getMessage());
        }
    }

    private function getForm(string $tab, bool $edit, Project $project): FormInterface|null
    {
        return match ($tab) {
            'about' => $edit ? $this->createForm(ProjectAboutType::class, $project) : null,
            'links' => $this->createForm(LinksType::class, $project),
            'contact' => $this->createForm(ContactType::class, [
                'entityId' => $project->getId(),
            ]),
            default => null,
        };
    }

    private function getTemplateName(string $tab, bool $edit): string
    {
        return match ($tab) {
            'about' => $edit ? '_about-edit' : '_about',
            'links' => $edit ? '_links-edit' : '_links',
            'contact' => '_contact',
            'settings' => '_settings',
            default => '_about',
        };
    }

    private function getAdditionalData(string $tab, Project $project): array
    {
        return match ($tab) {
            'settings' => [
                'projectSettings' => $this->getProjectSettings($project),
                'pagesUrlPrefix' => $this->pagesUrlPrefix,
            ],
            default => [],
        };
    }
}
