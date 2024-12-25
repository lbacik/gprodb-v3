<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MailingProvider;
use App\Entity\MailingRConfig;
use App\Form\MailingRType;
use App\Form\NewsletterProviderType;
use App\Service\MailingService;
use App\Service\ProjectService;
use App\Type\MailingProvider as MailingProviderEnum;
use App\Type\ProjectSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted('ROLE_USER')]
class SettingsNewsletterController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly MailingService $mailingService,
    ) {
    }

    #[Route('/projects/{id}/settings/newsletter/{provider?}', name: 'app_settings_newsletter', methods: ['GET', 'POST'])]
    public function newsletter(Request $request, string $id, string|null $provider): Response
    {
        $project = $this->projectService->getProject($id);
        $settings = $this->projectService->getProjectSettings($project);
        $mailingProvider = $this->getMailingProvider($settings, $provider);

        $formProvider = $this->createForm(
            NewsletterProviderType::class,
            $mailingProvider,
            [
                'action' => $this->generateUrl('app_settings_newsletter', ['id' => $id]),
                'method' => 'POST',
            ]
        );

        $formProvider->handleRequest($request);
        if ($formProvider->isSubmitted() && $formProvider->isValid()) {
            $mailingProviderName = $formProvider->get('newsletter')->getData();
        }

        $formProviderConfig = match($mailingProviderName ?? $mailingProvider?->getNewsletter() ?? MailingProviderEnum::GENERIC) {
            MailingProviderEnum::GENERIC => $this->createGenericForm($id),
            MailingProviderEnum::MAILINGR => $this->createMailingRForm(
                $project->getId(),
                $settings->getMailingRConfig(),
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
            MailingProviderEnum::GENERIC->value => $this->createGenericForm($id),
            MailingProviderEnum::MAILINGR->value => $this->createMailingRForm($id, null),
        };
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->projectService->getProject($id);
            $settings = $this->projectService->getProjectSettings($project);

            $this->mailingService->save(
                $settings,
                MailingProviderEnum::from($provider),
                $form->getData(),
            );

            return $this->redirectToRoute('app_project_details', ['id' => $id, 'tab' => 'settings']);
        }

        return $this->redirectToRoute('app_settings_newsletter', ['id' => $id, 'provider' => $provider]);
    }

    private function getMailingProvider(ProjectSettings $settings, string|null $provider): MailingProvider|null
    {
        $providerName = null;

        if ($provider) {
            $providerName = MailingProviderEnum::tryFrom($provider);
        }

        return $providerName !== null
            ? (new MailingProvider())->setNewsletter($providerName)
            : $settings->getMailingProvider();
    }

    private function createGenericForm(string $id): FormInterface
    {
        return $this->createFormBuilder(null, [
            'action' => $this->generateUrl(
                'app_settings_newsletter_config',
                [
                    'id' => $id,
                    'provider' => MailingProviderEnum::GENERIC->value,
                ]
            ),
            'method' => 'POST',
        ])->getForm();
    }

    private function createMailingRForm(string $id, MailingRConfig|null $mailingRConfig): FormInterface
    {
        return $this->createForm(
            MailingRType::class,
            $mailingRConfig,
            [
                'action' => $this->generateUrl(
                    'app_settings_newsletter_config',
                    [
                        'id' => $id,
                        'provider' => MailingProviderEnum::MAILINGR->value,
                    ]
                ),
                'method' => 'POST',
            ]
        );
    }
}
