<?php

namespace PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller {

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository('PaymentBundle:Payment')->getMyPayments($this->getUser());

        return $this->render('PaymentBundle:Payment:payments.html.twig', [
            'payments' => $payments,
        ]);
    }

}
