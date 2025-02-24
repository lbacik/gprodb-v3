<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\EmailType;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

trait EmailForm
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function emailForm(
        Request $request,
        string $title,
        string $successMessage,
        $callback,
        string $submit = 'Submit',
    ): Response {
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            try {
                $callback($email);

                $this->addFlash('success', $successMessage);
                return $this->redirect('app_login');
            } catch (Exception $exception) {
                $this->addFlash('error', 'Something went wrong!');
            }
        }

        return $this->render('form/base.html.twig', [
            'form' => $form,
            'title' => $this->translator->trans($title),
            'submit' => $this->translator->trans($submit),
        ]);
    }
}
