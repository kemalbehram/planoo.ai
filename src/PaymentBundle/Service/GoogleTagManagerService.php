<?php

namespace PaymentBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class GoogleTagManagerService extends \Twig_Extension {
    private $em;
    private $requestStack;

    public function __construct(EntityManager $em, RequestStack $requestStack) {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function getFunctions() {
        return array(new \Twig_SimpleFunction('getPaidCart', array($this, 'getPaidCart')));
    }

    public function getPaidCart() {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $paidCartId = $currentRequest->get('paidCart');
        if (!$paidCartId) {
            return null;
        }

        $cart = $this->em->getRepository('PaymentBundle:Cart')->find($paidCartId);

        return $cart;
    }

}