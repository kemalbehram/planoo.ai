<?php

namespace PromotionBundle\Controller\Frontend;

use PaymentBundle\Service\CartService;
use PromotionBundle\Entity\Coupon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Coupon controller.
 *
 */
class CouponController extends Controller {
    /**
     * Check the coupon from the POST request from the cart step to apply it on the final price
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkerCouponAction(Request $request) {
        //        if(!$request){
        //            return $this->redirect($this->generateUrl('front_panier_index'));
        //        }
        $em = $this->getDoctrine()->getManager();

        $session = $request->getSession();

        $code = $request->request->get('code');

        if ($code == null or $code == '') {
            $this->addFlash(
                'couponError',
                'Désolé. Ce code promotionnel n\'existe pas, ou n\'est plus actif.'
            );
            $response = null;
        } else {
            // Get the code promo available
            $coupon = $em
                ->getRepository('PromotionBundle:Coupon')
                ->findOneBy(['code' => $code]);

            // Check if coupon is available
            $response = $this->is_available($coupon, $this->getUser());
        }

        if ($response instanceof Coupon) {
            $cartService = $this->container->get(CartService::class);
            $cart = $cartService->getCurrentCart($this->getUser());
            $cart->getReductionAmountTTC();
            $cart->setCoupon($coupon);
            $em->persist($cart);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('financial_cart_index'));
    }

    /**
     * Verifie si le code promo existe bien
     * @param $coupon
     * @return string
     */
    private function is_available($coupon, $user) {
        $response = null;
        //a coupon is available if the number used < number max used
        if ($coupon == null || ($coupon->getNbMaxUsed() > 0 && $coupon->getNbUsed() >= $coupon->getNbMaxUsed())) {
            $this->addFlash(
                'couponError',
                'Désolé. Ce code n\'existe pas, ou n\'est plus actif.'
            );
        } elseif ($user->getCoupons()->contains($coupon)) {
            $this->addFlash(
                'couponError',
                'Désolé. Vous avez déjà utilisé ce code.'
            );
        } else {
            $response = $coupon;
        }
        return $response;
    }

    /**
     * Remove the code promo from the session
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeCouponAction(Request $request, $idCoupon, $idCart) {
        $em = $this->getDoctrine()->getManager();

        $cart = $em->getRepository('PaymentBundle:Cart')->find($idCart);
        $cart->setCoupon(null);
        $em->persist($cart);
        $em->flush();

        return $this->redirect($this->generateUrl('financial_cart_index'));
    }

    public function markCouponAsSentAction(
        Request $request,
        $currentRoute,
        $idCoupon
    ) {
        $em = $this->getDoctrine()->getManager();

        $coupon = $em->getRepository('PromotionBundle:Coupon')->find($idCoupon);
        $coupon->setSent(new \DateTime());
        $em->persist($coupon);
        $em->flush();

        return $this->redirect($this->generateUrl($currentRoute));
    }
}
