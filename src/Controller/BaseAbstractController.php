<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\JsonHub\Exception\AuthenticationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

abstract class BaseAbstractController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    protected function executeTokenSensitiveAction($callback): mixed
    {
        try {
            return $callback();
        } catch (AuthenticationException $exception) {
            return $this->reauthenticate();
        }
    }

    protected function reauthenticate(): RedirectResponse
    {
        $this->tokenStorage->setToken(null);
        $this->requestStack->getSession()->invalidate();

        $this->addFlash('error', 'Your authentication token has expired. Please login again.');
        $this->saveTargetPath(
            $this->requestStack->getSession(),
            $this->security->getFirewallConfig($this->requestStack->getCurrentRequest())?->getName(),
            $this->requestStack->getCurrentRequest()->getPathInfo(),
        );

        return $this->redirectToRoute('app_login');
    }
}
