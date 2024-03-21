<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\AboutType;
use App\Form\HeroType;
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

    #[Route('/projects/{id}/settings/landing-page', name: 'app_settings_landing_page', methods: ['GET'])]
    public function landingPage(
        string $id,
        #[MapQueryParameter()] string $tab = 'base',
    ): Response {

        $project = $this->projectService->getProject($id);

        match($tab) {
            'base' => $form = $this->createForm(LandingPageType::class),
            'hero' => $form = $this->createForm(HeroType::class),
            'about' => $form = $this->createForm(AboutType::class),
            default => $form = null,
        };

        return $this->render('settings/landing-page.html.twig', [
            'projectId' => $id,
            'project' => $project,
            'tab' => $tab,
            'form' => $form,
        ]);
    }

    #[Route('/projects/{id}/settings/landing-page/create', name: 'app_settings_landing_page_create', methods: ['POST'])]
    public function landingPageCreate(Request $request, int $id): Response
    {
        if (!$this->isCsrfTokenValid('landing-page-create', $request->request->get('token'))) {
            return new Response(null, 400);
        }

        return $this->redirect($this->generateUrl('app_settings_landing_page', ['id' => $id]));
    }
}
