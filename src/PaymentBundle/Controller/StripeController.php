<?php

namespace PaymentBundle\Controller;

use DateTime;
use PaymentBundle\Entity\Payment;
use PaymentBundle\Service\CartService;
use PromotionBundle\Entity\Catalog;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StripeController extends Controller {

    public function stripeCreateCheckoutSessionAction(Request $request, $cartId) {

        $em = $this->getDoctrine()->getManager();

        $cart = $em->getRepository('PaymentBundle:Cart')->find($cartId);
        $line_items = [];

        $description = '';

        foreach ($cart->getCommandeCatalogs() as $i => $commandeCatalog) {
            if ($i == 0) {
                $description = strip_tags($commandeCatalog->getCatalog()->getName());
            } else {
                $description = $description . ' - ' . strip_tags($commandeCatalog->getCatalog()->getName());
            }
        }

        array_push($line_items, [
            'name' => 'Planoo.ai',
            'description' => $description,
            'amount' => number_format($cart->getTotalAmountTTC(), 2, '', ''), // Formatage du montant au format Stripe
            'currency' => 'eur',
            'quantity' => 1,
        ]);

        // Set the API_key
        Stripe::setApiKey($this->container->getParameter('stripe_private_key'));

        $session = \Stripe\Checkout\Session::create([
            'payment_intent_data' => [
                'capture_method' => 'automatic',
            ],
            'customer_email' => $user = $this->getUser()->getEmail(),
            'line_items' => $line_items,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $request->getScheme() . '://' . $request->getHttpHost() . $this->generateUrl('payment_stripe_success', ['cartId' => $cartId]),
            'cancel_url' => $request->getScheme() . '://' . $request->getHttpHost() . $this->generateUrl('financial_cart_index'),
        ]);

        $payment_intent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        $payment = $cart->getPayment();
        if (!$payment) {
            $payment = new Payment();
            $payment->setStatut(Payment::PAYMENT_STATUT_INITIATE);
        }
        $payment->setAmount($payment_intent->amount);
        $payment->setCart($cart);
        $payment->setPaymentDate(new DateTime());
        $payment->setStripeToken($payment_intent->id);
        $cart->setPayment($payment);
        $em->persist($cart);
        $em->flush();

        return $this->render('PaymentBundle:Gateways:stripe_checkout.html.twig', [
            'stripe_checkout_session' => $session,
        ]);
    }

    public function stripeSuccessfullPaiementAction(Request $request, $cartId) {
        //Vérifier si le paiement est effectivement réalisé
        Stripe::setApiKey($this->container->getParameter('stripe_private_key'));

        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $cart = $em->getRepository('PaymentBundle:Cart')->find($cartId);

        $payment = $cart->getPayment();

        if ($payment) {

            if ($payment->getProcessingDate()) {
                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }

            $payment_intent = \Stripe\PaymentIntent::retrieve($payment->getStripeToken());

            if ($payment_intent->status === 'succeeded' || $payment_intent->status === 'requires_capture') {

                $cartService = $this->container->get(CartService::class);
                
                // Default redirect
                $urlRedirect = $this->generateUrl('user_my_projects', ['paidCart' => $cartId]);

                // If we came from a referenced page (BP internal service, etc.) we come back to it
                $routeInfos = $cartService->getAndCleanReferer($request);
                if ($routeInfos) {
                    $routerRoute = $routeInfos['_route'];
                    //Remove useless infos
                    unset($routeInfos['_route'], $routeInfos['_controller']);
                    // Add paid Cart infos
                    $routeInfos['paidCart'] = $cartId;
                    $urlRedirect = $this->generateUrl($routerRoute, $routeInfos);
                }

                // Update the command & coupons
                $cartService->updateCommand($cart, $payment);

                $message = (new \Swift_Message('Planoo.ai - Achat de ' . $cart->getUser()->getEmail()))
                    ->setTo('sales@planoo.ai')
                    ->setFrom('noreply@' . $this->getParameter('mailer_domain'), 'Planoo.ai')
                    ->setBody($this->renderView('PaymentBundle:Mails:reporting_purchase_notification.html.twig', [
                        'cart' => $cart,
                    ]
                    ), 'text/html'
                    );

                $this->get('mailer')->send($message);

                // Clear the session
                $this->clearDataFromSessionAndPanier($session);

                $payment->setProcessingDate(new DateTime());

                if ($payment_intent->status === 'succeeded') {
                    $payment->setStatut(Payment::PAYMENT_STATUT_FINISH);
                } else {
                    $payment->setStatut(Payment::PAYMENT_STATUT_CAPTURED);
                }

                $em->persist($payment);
                $em->persist($cart);
                $em->flush();

                return $this->redirect($urlRedirect);
            }
        }

        return $this->redirect($this->generateUrl('payment_stripe_checkout_form', ['cartId' => $cartId]));
    }

    /**
     * Clear the panier in the session after the success response
     * @param $session
     */
    private function clearDataFromSessionAndPanier($session) {
        if ($session->has('bp_formulaire')) {
            $session->remove('bp_formulaire');
        }

        if ($session->has('code_promo')) {
            $session->remove('code_promo');
        }

    }

}
