<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use App\Service\SendContactRequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        SendContactRequestService $contactRequestService,
        string $gprodbEntityUuid,
    ): Response {

        $form = $this->createForm(ContactType::class, [
            'entityId' => $gprodbEntityUuid,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $contact = $form->getData();
             $contactRequestService->send($contact);
             $this->addFlash('success', 'Your message has been sent!');

            return $this->render(
                'contact/index.html.twig',
                [
                    'form' => $this->createForm(
                        ContactType::class,
                        [
                            'entityId' => $gprodbEntityUuid,
                        ]
                    ),
                ],
                new Response(null, Response::HTTP_CREATED)
            );
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
