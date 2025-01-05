<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\SetPasswordType;
use App\Infrastructure\JsonHub\Service\JsonHubUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordController extends AbstractController
{
    use EmailForm;

    public function __construct(
        private readonly JsonHubUser $jsonHubUser,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/forgot/password/request', name: 'app_forgot_password_request')]
    public function sendForgotPasswordLink(Request $request): Response
    {
        return $this->emailForm(
            $request,
            'Send reset password link',
            'Reset password link was sent!',
            fn (string $email) => $this->jsonHubUser->sendForgotPassword($email),
        );
    }

    #[Route('/forgot/password', name: 'app_forgot_password_form')]
    public function forgotPasswordForm(
        Request $request,
        #[MapQueryParameter] string|null $token = null,
    ): Response {
        $form = $this->createForm(SetPasswordType::class, [
            'token' => $token,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $this->jsonHubUser->forgotPassword(
                $data[SetPasswordType::TOKEN],
                $data[SetPasswordType::PASSWORD]
            );
        }

        return $this->render('form/base.html.twig', [
            'form' => $form,
            'title' => 'Reset password',
        ]);
    }
}
