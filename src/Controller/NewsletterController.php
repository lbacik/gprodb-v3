<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NewsletterService;
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
    ): Response {
        $csrfToken = $request->getPayload()->get('csrf_token');

        if (!$this->isCsrfTokenValid('newsletterSubscribe', $csrfToken)) {
            $this->addFlash('error', $translator->trans('newsletter.error'));

            return $this->redirectToRoute($request->get('_route'));
        }

        $email = $request->request->get('email');
        $newsletterService->subscribe($email);

        $this->addFlash('success', $translator->trans('newsletter.success'));

        $referer = $request->headers->get('referer');
        return $referer
            ? $this->redirect($referer)
            : $this->redirectToRoute('app_home');
    }
}
