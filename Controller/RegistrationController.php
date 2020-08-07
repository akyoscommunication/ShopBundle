<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Form\RegistrationFormType;
use Akyos\ShopBundle\Repository\BaseUserShopRepository;
use Akyos\ShopBundle\Security\EmailVerifier;
use Akyos\ShopBundle\Security\UserShopAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * @Route("/authentification", name="shop_")
 */
class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/sign-up", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UserShopAuthenticator $authenticator
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserShopAuthenticator $authenticator): Response
    {
        $user = new BaseUserShop();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $email = $user->getEmail();
            $user->setRoles(['ROLE_CUSTOMER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('shop_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@your-domain.com', 'FUS Mail'))
                    ->to($email)
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('@AkyosShop/registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            $this->addFlash('success', "Un email à été envoyé à ${email} afin de vérifier votre compte.");

//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'shop' // firewall name in security.yaml
//            );

            return $this->redirectToRoute('shop_login');
        }

        return $this->render('@AkyosShop/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="verify_email")
     * @param Request $request
     *
     * @param BaseUserShopRepository $baseUserShopRepository
     *
     * @return Response
     */
    public function verifyUserEmail(Request $request, BaseUserShopRepository $baseUserShopRepository): Response
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $user = $baseUserShopRepository->findOneBy(['verifyToken'=> $request->get('token')]);

            $user->setIsVerified(true);

            $this->getDoctrine()->getManager()->flush();
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('shop_register');
        }

        $this->addFlash('success', 'Votre email à bien été vérifié.');

        return $this->redirectToRoute('account_front_index');
    }
}
