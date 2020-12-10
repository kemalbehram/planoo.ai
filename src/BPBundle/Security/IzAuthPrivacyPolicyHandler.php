<?php

namespace BPBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class IzAuthPrivacyPolicyHandler implements AuthenticationSuccessHandlerInterface, AuthenticationEntryPointInterface {

    protected $em = null;
    protected $router = null;

    public function __construct(Router $router, EntityManager $em) {
        $this->em = $em;
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response {

        $url = $this->router->generate('user_my_projects');
        $previous = $request->getSession()->get('previous');

        if ($previous) {
            $url = $previous;
        }

        $page = $this->em->getRepository('BackBundle:Page')->myFindOneBy('politique-de-confidentialite');

        if ($page) {
            if ($token->getUser()->getPrivacyPolicyAcceptedAt() == null || $token->getUser()->getPrivacyPolicyAcceptedAt() < $page->getUpdatedAt()) {
                return new RedirectResponse($this->router->generate('privacy_policy_update'));
            }
        }

        return new RedirectResponse($url);
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        $request->getSession()->set('previous', $request->getUri());
        $url = $this->router->generate('ver_core_login');
        return new RedirectResponse($url);
    }

}
