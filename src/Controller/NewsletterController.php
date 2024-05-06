<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\NewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsletterController extends AbstractController
{
    #[Route('/subscribe', name: 'app_subscribe', methods: ['POST'])]
    public function subscribe(Request $request, NewsletterService $newsletterService): Response
    {
        $csrfToken = $request->getPayload()->get('csrf_token');

        if (!$this->isCsrfTokenValid('newsletterSubscribe', $csrfToken)) {
            throw new \RuntimeException('CSRF token is invalid');
        }

        $email = $request->request->get('email');
        $newsletterService->subscribe($email);

        $this->addFlash('success', 'You have been successfully subscribed to our newsletter');

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
