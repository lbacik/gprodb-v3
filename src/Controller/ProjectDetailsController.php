<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectDetailsController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {
    }

    #[Route('/projects/{id}/{tab}', name: 'app_project_details', methods: ['GET'])]
    public function index(string $id, string|null $tab = 'about'): Response
    {
        $project = $this->projectService->getProject($id);

        if ($project === null) {
            throw $this->createNotFoundException('The project does not exist');
        }

        if ($tab === 'contact') {
            $contactForm = $this->createForm(ContactType::class);
        }

        return $this->render('project_details/index.html.twig', [
            'projectId' => $id,
            'tab' => $tab,
            'project' => $project,
            'contactForm' => $contactForm ?? null,
        ]);
    }

    #[Route('/projects/{id}/contact', name: 'app_project_contact', methods: ['POST'])]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Your message has been sent');
            return new Response(null, 204);
        }

        return new Response(null, 400);
    }
}
