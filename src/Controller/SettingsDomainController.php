<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\DomainType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class SettingsDomainController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects/{id}/settings/domain', name: 'app_settings_domain', methods: ['GET', 'POST'])]
    public function domain(Request $request, string $id): Response
    {
        $project = $this->projectService->getProject($id);
        $settings = $this->projectService->getProjectSettings($project);

        $form = $this->createForm(
            DomainType::class,
            $settings->getDomain(),
            [
                'action' => $this->generateUrl('app_settings_domain', ['id' => $id]),
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $delete = $request->request->get('delete', '') == 'delete';

            if ($delete) {
                $this->projectService->deleteDomain($project);
            } else {
                $this->projectService->setDomain($project, $form->getData());
            }

            return $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'settings']);
        }

        return $this->render('settings/domain/domain.html.twig', [
            'project' => $project,
            'settings' => $settings,
            'form' => $form,
        ]);
    }
}
