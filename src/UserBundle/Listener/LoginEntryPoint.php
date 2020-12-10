<?php

namespace UserBundle\Listener;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * When the user is not authenticated at all (i.e. when the security context has no token yet),
 * the firewall's entry point will be called to start() the authentication process.
 */
class LoginEntryPoint implements AuthenticationEntryPointInterface {

    protected $router;

    public function __construct($router) {
        $this->router = $router;
    }

    /*
     * This method receives the current Request object and the exception by which the exception
     * listener was triggered.
     *
     * The method should return a Response object
     */

    public function start(Request $request, AuthenticationException $authException = null) {

        if ($request->attributes->get('_route') == "promotion_catalog_index" || $request->attributes->get('_route') == "bp_init") {
            return new RedirectResponse($this->router->generate('fos_user_registration_register'),301);
        } else {
            return new RedirectResponse($this->router->generate('fos_user_security_login'),301);
        }
    }

}
