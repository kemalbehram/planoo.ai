<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 10/10/16
 * Time: 16:56
 */

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\Payment;
use PaymentBundle\Form\PaymentType;
use PaymentBundle\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller {

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $referer = $request->headers->get('referer');
        // $session = $request->getSession()->set('purchase_origin', $referer);
        
        $cartService = $this->container->get(CartService::class);
        $cartService->storeReferer($request);

        $cart = $cartService->getCurrentCart($this->getUser());

        //configure payment
        $payment = new Payment();
        $payment->setStatut(Payment::PAYMENT_STATUT_INITIATE);
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        return $this->render('PaymentBundle:Cart:index.html.twig', [
            'user' => $this->getUser(),
            'cart' => $cart,
        ]);
    }

}
