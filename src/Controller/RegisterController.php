<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RegisterType;
use App\Infrastructure\JsonHub\Service\JsonHubRegister;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterController extends AbstractController
{
    use EmailForm;

    public function __construct(
        private readonly JsonHubRegister $jsonHubRegister,
        private readonly LoggerInterface $logger,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $this->jsonHubRegister->register(
                    $data['email'],
                    $data['plainPassword'],
                );

                $this->addFlash(
                    'success',
                    'Registration was successful! Check your email for a verification link.'
                );

                return $this->redirectToRoute('app_login');
            } catch (Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->addFlash('error', 'Something went wrong!');
            }
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $form,
        ]);
    }

    #[Route('/register/confirm', name: 'app_register_confirm', methods: ['GET'])]
    public function confirmEmail(
        #[MapQueryParameter] string $token
    ): Response {
        $this->jsonHubRegister->activate($token);

        $this->addFlash('success', 'Your email has been confirmed!');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/register/confirm/resend', name: 'app_register_confirm_resend', methods: ['GET', 'POST'])]
    public function resendConfirmEmail(Request $request): Response
    {
        return $this->emailForm(
            $request,
            'Resend account confirmation email',
            'Email confirmation link was sent!',
            fn (string $email) => $this->jsonHubRegister->resend($email),
            'Send'
        );
    }
}
