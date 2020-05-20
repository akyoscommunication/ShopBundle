<?php

namespace Akyos\ShopBundle\Controller;

use Akyos\ShopBundle\Entity\BaseUserShop;
use Akyos\ShopBundle\Form\RegistrationFormType;
use Akyos\ShopBundle\Security\ShopBundleAuthenticator;
use Akyos\ShopBundle\Security\UserShopAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/mon-compte", name="shop_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/sign-up", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UserShopAuthenticator $authenticator
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
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'shop' // firewall name in security.yaml
            );
        }

        return $this->render('@AkyosShop/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
