<?php

namespace Akyos\ShopBundle\Security;

use Akyos\CmsBundle\Entity\User;
use Akyos\ShopBundle\Entity\BaseUserShop;
use App\Entity\User\Hotelier;
use App\Entity\User\Organizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserShopAuthenticator extends AbstractLoginFormAuthenticator
{
	use TargetPathTrait;

	public const LOGIN_ROUTE = 'shop_login';

	private $entityManager;
	private $urlGenerator;
	private $csrfTokenManager;

	public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager)
	{
		$this->entityManager = $entityManager;
		$this->urlGenerator = $urlGenerator;
		$this->csrfTokenManager = $csrfTokenManager;
	}

	public function supports(Request $request): bool
	{
		return self::LOGIN_ROUTE === $request->attributes->get('_route')
			&& $request->isMethod('POST');
	}

	public function authenticate(Request $request): Passport
	{
		$email = $request->request->get('email');

		return new Passport(
			new UserBadge($email, function (string $email) {
				$user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

				if ($user) {
					return $user;
				}

				if (!$user) {
					// fail authentication with a custom error
					throw new CustomUserMessageAuthenticationException("Le compte n'existe pas.");
				}

				if (!$user->isVerified()) {
					throw new CustomUserMessageAuthenticationException("Vous n'avez pas vérifier votre compte.");
				}
			}),
			new PasswordCredentials($request->request->get('password', '')),
			[
				new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
			]
		);
	}


	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
	{
		if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
			return new RedirectResponse($targetPath);
		}

		return new RedirectResponse($this->urlGenerator->generate('home'));
	}

	protected function getLoginUrl(Request $request): string
	{
		return $this->urlGenerator->generate(self::LOGIN_ROUTE);
	}
}
