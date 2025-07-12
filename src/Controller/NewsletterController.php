<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\MailingSubscriptionType;
use App\Service\NewsletterService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterController extends AbstractController
{
    #[Route('/subscribe', name: 'app_subscribe', methods: ['POST'])]
    public function subscribe(
        Request $request,
        NewsletterService $newsletterService,
        TranslatorInterface $translator,
        LoggerInterface $logger,
    ): Response {
        $form = $this->createForm(MailingSubscriptionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $translator->trans('newsletter.error'));

            return $this->redirectToRoute($request->get('_route'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            try {
                $newsletterService->subscribe($email);
                $this->addFlash('success', $translator->trans('newsletter.success'));
            } catch (\Throwable $throwable) {
                $this->addFlash('error', $translator->trans('newsletter.error'));
                $logger->error($throwable->getMessage(), ['exception' => $throwable]);

                return $this->redirectToRoute($request->get('_route'));
            }
        }

        $referer = $request->headers->get('referer');

        return $referer
            ? $this->redirect($referer)
            : $this->redirectToRoute('app_home');
    }
}
