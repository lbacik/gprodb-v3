<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\MailingRType;
use App\Form\NewsletterProviderType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsNewsletterController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects/{id}/settings/newsletter/{provider?}', name: 'app_settings_newsletter', methods: ['GET', 'POST'])]
    public function domain(Request $request, string $id, string|null $provider): Response
    {
        $project = $this->projectService->getProject($id);
        $newsletter = $this->projectService->getProjectSettings($project)->getNewsletter();

        $formProvider = $this->createForm(NewsletterProviderType::class, [
            'provider' => $provider ?? $newsletter?->getProvider(),
        ], [
            'action' => $this->generateUrl('app_settings_newsletter', ['id' => $id]),
            'method' => 'POST',
        ]);

        $formProvider->handleRequest($request);
        if ($formProvider->isSubmitted() && $formProvider->isValid()) {
            $provider = $formProvider->get('provider')->getData();
        }

        $formProviderConfig = match($provider ?? 'generic') {
            'generic' => $this->createGenericForm($id),
            'mailingr' => $this->createMailingRForm(
                $id,
                $newsletter?->getConfig()['apiKey'] ?? '',
                $newsletter?->getConfig()['apiSecret'] ?? ''
            ),
        };

        return $this->render('settings/newsletter/newsletter.html.twig', [
            'project' => $project,
            'formProvider' => $formProvider,
            'formProviderConfig' => $formProviderConfig->createView(),
        ]);
    }

    #[Route('/projects/{id}/settings/newsletter/config/{provider}', name: 'app_settings_newsletter_config', methods: ['POST'])]
    public function newsletterConfig(Request $request, string $id, string $provider): Response
    {
        $form = match($provider) {
            'generic' => $this->createGenericForm($id),
            'mailingr' => $this->createMailingRForm($id, '', ''),
        };

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->projectService->getProject($id);
            $this->projectService->setNewsletter($project, $provider, $form->getData());

            $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'settings']);
        }

        return $this->redirectToRoute('app_settings_newsletter', ['id' => $id, 'provider' => $provider]);
    }

    private function createGenericForm(string $id): FormInterface
    {
        return $this->createFormBuilder(null, [
            'action' => $this->generateUrl('app_settings_newsletter_config', ['id' => $id, 'provider' => 'generic']),
            'method' => 'POST',
        ])->getForm();
    }

    private function createMailingRForm(string $id, string $apiKey, string $apiSecret): FormInterface
    {
        return $this->createForm(MailingRType::class, [
            'apiKey' => $apiKey,
            'apiSecret' => $apiSecret,
        ], [
            'action' => $this->generateUrl('app_settings_newsletter_config', ['id' => $id, 'provider' => 'mailingr']),
            'method' => 'POST',
        ]);
    }
}
