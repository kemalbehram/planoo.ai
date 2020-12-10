<?php

/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 12/11/16
 * Time: 13:04
 */

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\Payment;
use PaymentBundle\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function upgradeBPFormulaAction($businessPlanId, $catalogId) {
        $em = $this->getDoctrine()->getManager();
        $businessPlan = $em->getRepository('BPBundle:BusinessPlan')->find($businessPlanId);
        $catalog = $em->getRepository('PromotionBundle:Catalog')->find($catalogId);

        $cartService = $this->container->get(CartService::class);
        $cartService->upgradeBP($businessPlan, $catalog, true);

        return $this->redirect($this->generateUrl('user_my_projects'));
    }

    public function paymentFreemiumAction(Request $request, $id) {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_my_projects');
        }

        $em = $this->getDoctrine()->getManager();

        // BP
        $cart = $em->getRepository('PaymentBundle:Cart')->find($id);

        if ($cart == null || $cart->getTotalAmountTTC() > 0) {
            return $this->redirect($this->generateUrl('financial_cart_index'));
        }

        if (sizeof($cart->getCommandeCatalogs()) > 0) {
            $payment = new Payment();
            $payment->setAmount(0);
            $payment->setStripeToken("FREEMIUM_PAYMENT");
            $payment->setCart($cart);
            $payment->setStatut(Payment::PAYMENT_STATUT_FINISH);

            $cartService = $this->container->get(CartService::class);
            $cartService->updateCommand($cart, $payment);

            $em->persist($cart);
            $em->flush();

            $message = (new \Swift_Message('Planoo.ai - Achat de ' . $cart->getUser()->getEmail()))
                ->setTo('sales@planoo.ai')
                ->setFrom('noreply@' . $this->getParameter('mailer_domain'), 'Planoo.ai')
                ->setBody($this->renderView('PaymentBundle:Mails:reporting_purchase_notification.html.twig', [
                    'cart' => $cart,
                ]
                ), 'text/html'
                );

            $this->get('mailer')->send($message);

            $this->addFlash('confirmation', 'Votre commande a été validée');
        }
        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

}
