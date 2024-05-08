<?php

declare(strict_types=1);

namespace App\Infrastructure\JsonHub\Security;

use App\Entity\User;
use App\Infrastructure\JsonHub\Service\JSONHubService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Throwable;

class JsonHubAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly JSONHubService $jsonHubClient,
        private readonly RouterInterface $router,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($email),
            new CustomCredentials(
                function ($credentials, User $user) {
                    try {
                        $accessToken = $this->jsonHubClient
                            ->getClient()
                            ->getOAuthToken(
                                $user->getUserIdentifier(),
                                $credentials
                            );

                    } catch (Throwable $exception) {
                        throw new BadCredentialsException();
                    }

                    if ($accessToken === null) {
                        throw new BadCredentialsException();
                    }

                    $user->setJsonHubAccessToken($accessToken);

                    return true;
                },
                $password
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
//    {
//        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
//
//        return new RedirectResponse($this->router->generate('app_login'));
//    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
