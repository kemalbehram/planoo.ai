<?php

namespace PaymentBundle\Service;

use Doctrine\ORM\EntityManager;
use PaymentBundle\Entity\Cart;
use PromotionBundle\Entity\Catalog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Psr\Log\LoggerInterface;

class CartService {
    private $em;
    private $router;
    private $logger;

    public function __construct(Router $router, EntityManager $em, LoggerInterface $logger) {
        $this->router = $router;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getCurrentCart($user) {
        $cart = $this->em
            ->getRepository('PaymentBundle:Cart')
            ->getCurrentCart($user);
        if ($cart == null) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->em->persist($cart);
            $this->em->flush();
        }
        return $cart;
    }

    public function storeReferer(Request $request) {
        $referer = $request->headers->get('referer');

        if ($referer) {
            $refererPathInfo = Request::create($referer)->getPathInfo();
            // Remove the scriptname if using a dev controller like app_dev.php (Symfony 3.x only) and remose "/bo"
            $refererPathInfo = str_replace('/bo', '', $refererPathInfo);
            $refererPathInfo = str_replace($request->getScriptName(), '', $refererPathInfo);
            
            // try to match the path with the application routing
            try{
                $routeInfos = $this->router->match($refererPathInfo);

                if (array_key_exists('purchaseOrigin', $routeInfos)) {
                    if ($routeInfos['purchaseOrigin']) {
                        $session = $request->getSession()->set('purchaseOrigin', $referer);
                        return true;
                    }
                }
            } catch (ResourceNotFoundException  $e){
                $this->logger->error('Error in CartService. No route found for referer : \'' . $refererPathInfo .'\'');
            }
        }
        $session = $request->getSession()->set('purchaseOrigin', null);
        return false;
    }

    public function getAndCleanReferer(Request $request) {
        $referer = $request->getSession()->get('purchaseOrigin');
        // Can't be use twice
        $request->getSession()->set('purchaseOrigin', null);

        if ($referer) {
            $refererPathInfo = Request::create($referer)->getPathInfo();
            // Remove the scriptname if using a dev controller like app_dev.php (Symfony 3.x only)
            $refererPathInfo = str_replace('/bo', '', $refererPathInfo);
            $refererPathInfo = str_replace($request->getScriptName(), '', $refererPathInfo);
            // try to match the path with the application routing
            $routeInfos = $this->router->match($refererPathInfo);
            return $routeInfos;
        }
        return null;
    }

    /**
     * Update the comand in the database after the success response
     * @param $cart
     * @param $payment
     * @return Cart
     */
    public function updateCommand($cart, $payment) {

        // Update Cart Payment
        $paymentDate = new \DateTime();
        $payment->setPaymentDate($paymentDate);
        $cart->setPayment($payment);

        foreach ($cart->getCommandeCatalogs() as $commandeCatalog) {
            try {
                $this->upgradeBP($commandeCatalog->getBusinessPlan(), $commandeCatalog->getCatalog(), false);
            } catch (\Doctrine\ORM\EntityNotFoundException $e) {
                $cart->removeCommandeCatalog($commandeCatalog);
            }
        }

        // Update Coupn NB of use
        if ($cart->getCoupon() != null) {
            $coupon = $cart->getCoupon();
            $coupon->addUser($cart->getUser());
            $coupon->setNbUsed($coupon->getNbUsed() + 1);

            if ($coupon->getPartner() != null
                && ($cart->getUser()->getPartner() == null
                    || ($cart->getUser()->getPartner() != null && $cart->getUser()->getPartner()->getId() == 1)
                )
            ) {
                $cart->getUser()->setPartner($coupon->getPartner());
            }
        }

        return $cart;
    }

    /**
     * Update the comand in the database after the success response
     * 3 Options :
     * - End of trial for BP
     * - Upgrade BP Formula
     * - Buy / Add Service
     * @param $businessPlan
     * @param $catalog
     * @param $persistTrial If True, user just want to upgrade his Trial Formula
     * @return BusinessPlan
     */

    public function upgradeBP($bp, $catalog, $persistTrial) {
        $oldCatalog = $bp->getCatalog();
        $service = $bp->getService();

        // End of Trial or trial upgrade
        if ($catalog->getType() === Catalog::CATALOG_TYPE_BP) {
            $bp->setCatalog($catalog);
            // If user pay to end trial
            if (!$persistTrial) {
                $bp->setState('validate');
                $service->setExpireTrialDate(null);
                $this->em->persist($service);
            }
            $this->em->persist($bp);
        }

        // BP Upgrade
        if ($catalog->getType() === Catalog::CATALOG_TYPE_BP_UPGRADE) {
            $bp->setCatalog($catalog->getCatalogDestination());
            $this->em->persist($bp);
        }

        // Increase Advice Hour
        if ($catalog->getNbAdviceHour()) {
            // If we are in upgrade and OLD Catalog already has adive hour, we do not update advice hour (e.g. Pro -> Premium)
            if (!($catalog->getType() === Catalog::CATALOG_TYPE_BP_UPGRADE && $oldCatalog->getNbAdviceHour() > 0)) {
                $service->setAdviceHour($catalog->getNbAdviceHour());
                $this->em->persist($service);
            }
        }

        // Increase Nb Wording Hour
        if ($catalog->getHasWording()) {
            // If we are in upgrade and OLD Catalog already has adive hour, we do not update advice hour (e.g. Pro -> Premium)
            if (!($catalog->getType() === Catalog::CATALOG_TYPE_BP_UPGRADE && $oldCatalog->getHasWording() > 0)) {
                $service->setNbWording(1);
                $this->em->persist($service);
            }
        }

        // // OLD update bp expiration if an extension has been bought
        // if ($catalog->getNbDayForExpireEditDate() > 0) {

        //     $expirationDate = $service->getExpireEditDate();

        //     if ($expirationDate == null || $expirationDate < new DateTime()) {
        //         $expirationDate = new \DateTime();
        //     }
        //     $expirationDate->add(new DateInterval('P' . $catalog->getNbDayForExpireEditDate() * $commandeCatalog->getQuantity() . 'D'));
        //     $service->setExpireEditDate($expirationDate);
        // }

        $this->em->flush();
        return $bp;
    }

}
