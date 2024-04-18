<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LandingPageType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class SettingsLandingPageController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects/{id}/settings/landing-page', name: 'app_settings_landing_page', methods: ['GET', 'POST'])]
    public function landingPage(
        Request $request,
        string $id,
        #[MapQueryParameter()] string $tab = 'base'
    ): Response {
        $project = $this->projectService->getProject($id);
        $settings = $this->projectService->getProjectSettings($project);
        $landingPage = $settings->getLandingPage();

        $form = $this->createForm(
            LandingPageType::class,
            [
                'base' => $landingPage,
                'hero' => $landingPage?->getHero(),
                'about' => $landingPage?->getAbout(),
            ],
            [
                'action' => $this->generateUrl('app_settings_landing_page', ['id' => $id]),
                'method' => 'POST',
                'attr' => [
                    'novalidate' => 'novalidate',
                ],
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->projectService->saveLandingPage($project->getId(), $form->getData());

            $this->addFlash('success', 'Landing page settings saved!');

            return $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'settings']);
        }

        return $this->render('settings/landing/landing-page.html.twig', [
            'project' => $project,
            'tab' => $tab,
            'form' => $form,
        ]);
    }
}
