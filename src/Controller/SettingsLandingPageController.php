<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\AboutType;
use App\Form\HeroType;
use App\Form\LandingPageBaseType;
use App\Form\LandingPageType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
        $landingPage = $project->getSettings()?->getLandingPage();

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
            return $this->redirectToRoute('app_settings_landing_page', ['id' => $id]);
        }

        return $this->render('settings/landing-page.html.twig', [
            'project' => $project,
            'tab' => $tab,
            'form' => $form,
        ]);
    }

    #[Route('/projects/{id}/settings/landing-page', name: 'app_settings_landing_page_save', methods: ['POST'])]
    public function landingPageSave(Request $request, int $id): Response
    {
        $form = $this->createForm(LandingPageBaseType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('session')->set('landing_page_data', $form->getData());
        }

        return $this->redirect($this->generateUrl('app_settings_landing_page', ['id' => $id]));
    }

    #[Route('/projects/{id}/settings/landing-page/create', name: 'app_settings_landing_page_create', methods: ['POST'])]
    public function landingPageCreate(Request $request, int $id): Response
    {
        if (!$this->isCsrfTokenValid('landing-page-create', $request->request->get('token'))) {
            return new Response(null, 400);
        }

        return $this->redirect($this->generateUrl('app_settings_landing_page', ['id' => $id]));
    }

    private function getForm(string $tab, array $landingPageData): FormInterface
    {
        $currentData = $this->mergeSessionData($tab, $landingPageData);

        return match($tab) {
            'base' => $this->createForm(LandingPageBaseType::class, $currentData[$tab]),
            'hero' => $form = $this->createForm(HeroType::class, $currentData[$tab]),
            'about' => $form = $this->createForm(AboutType::class, $currentData[$tab]),
        };
    }

    private function mergeSessionData(string $tab, array $landingPageData): array
    {
        $sessionData = $this->get('session')->get('landing_page_data', []);

        return array_merge($landingPageData, $sessionData);
    }
}
