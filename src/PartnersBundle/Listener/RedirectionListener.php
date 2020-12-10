<?php

namespace PartnersBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectionListener {

    /** @var  TokenStorageInterface */
    protected $tokenStorage;
    protected $authChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker) {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        $host = $request->getHost();

        $accessToken = $this->tokenStorage->getToken();
        /** @var User $user */
        if ($accessToken != null) {
            $user = $accessToken->getUser();

            if (is_object($user) && !$this->authChecker->isGranted('ROLE_ADMIN')) {
                $partner = $user->getPartner();

                if (is_object($partner) && $partner->getCustomDomain() != null) {
                    if ($host != $partner->getCustomDomain()) {
                        $event->setResponse(new RedirectResponse(str_replace($host, $partner->getCustomDomain(), $request->getURI())));
                    }
                }
            }
        }
    }

}
