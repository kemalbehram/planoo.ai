<?php

namespace UserBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class AuthenticationAttemptListener
 * @package ExampleBundle\EventListener
 */
class CaptchaFormAuthenticationListener implements EventSubscriberInterface {

    private $router;

    /**
     * @var GoogleReCaptcha
     */
    private $googleReCaptcha;

    /**
     * AuthenticationAttemptListener constructor.
     */
    public function __construct(UrlGeneratorInterface $router) {
        $this->router = $router;
        $this->googleReCaptcha = $recaptcha = new \ReCaptcha\ReCaptcha('6LeeNOwUAAAAAGDsLVdQNYNmTABJ2ssqnsFFtBc1');
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'
        );
    }

    /**
     * @param AuthenticationSuccessEvent $event
      /**
     * Check incoming google recaptcha token and reset attempt-counter on success or throw exception
     *
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event) {
        $this->checkCaptcha($event);
    }

    public function onRegistrationSuccess(FormEvent $event) {
        $this->checkCaptcha($event);
    }

    private function checkCaptcha($event) {
        $reCaptchaResponse = $event->getRequest()->get('g-recaptcha-response');
        $captchaRequest = $this->googleReCaptcha->verify($reCaptchaResponse, $event->getRequest()->getClientIp());


        if (!$captchaRequest->isSuccess()) {
            throw new CustomUserMessageAuthenticationException('Erreur de Captcha');
        }
    }

}
