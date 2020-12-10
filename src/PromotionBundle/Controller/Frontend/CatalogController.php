<?php

/**
 * Created by PhpStorm.
 * User: wac28
 * Date: 27/09/16
 * Time: 18:02
 */

namespace PromotionBundle\Controller\Frontend;

use PaymentBundle\Service\CartService;
use PromotionBundle\Checker\AmountTotalChecker;
use PromotionBundle\Checker\DateEligibilityChecker;
use PromotionBundle\Entity\Catalog;
use PaymentBundle\Entity\Cart;
use PromotionBundle\Entity\CommandeCatalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller {

    /**
     * Show catalog
     */
    public function indexAction($bpHash) {
        $em = $this->getDoctrine()->getManager();
        
        $catalog = null;
        $bp = null;
        $isAnUpgrade = false;
        if ($bpHash) {
            $bp = $em->getRepository('BPBundle:BusinessPlan')->findOneBy(['hash' => $bpHash]);
            if ($bp) {
                $catalog = $bp->getCatalog();
                // User want an upgrade only if it's a paid BP
                $isAnUpgrade = !$bp->isTrial();
            }
        }
        
        $catalogItems = null;
        if ($isAnUpgrade) {
            $catalogItems = $em->getRepository('PromotionBundle:Catalog')->getBPUpgradeItems($bp->getCatalog());
        } else {
            $catalogItems = $em->getRepository('PromotionBundle:Catalog')->getBusinessPlanItems();
        }

        return $this->render('PromotionBundle:Frontend:Catalog/catalog_index.html.twig', [
            'catalogItems' => $catalogItems,
            'catalog' => $catalog,
            'bp' => $bp,
            'isAnUpgrade' => $isAnUpgrade,
        ]);
    }

    /**
     * Add an item from the catalog
     * @param Request $request
     * @param $businessPlanId
     * @param $catalogId
     * @return Response
     */
    public function addCatalogItemAction(Request $request, $businessPlanId, $catalogId) {
        $em = $this->getDoctrine()->getManager();

        $cart = new Cart();
        $cart->setUser($this->getUser());
        $em->persist($cart);
        $em->flush();

        $catalog = $em->getRepository('PromotionBundle:Catalog')->find($catalogId);
        $businessPlan = $businessPlanId ? $em->getRepository('BPBundle:BusinessPlan')->find($businessPlanId) : null;

        if ($catalog) {
            $commandeCatalog = new CommandeCatalog();
            $commandeCatalog->setCatalog($catalog);
            $commandeCatalog->setCart($cart);
            $commandeCatalog->setBusinessPlan($businessPlan);
            $commandeCatalog->setQuantity(1);
            $cart->addCommandeCatalog($commandeCatalog);

            $em->persist($cart);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('financial_cart_index'));
    }

    /**
     * Remove an item from the catalog
     * @param Request $request
     * @param $itemId
     * @return Response
     */
    public function removeCatalogItemAction(Request $request, $itemId) {
        $em = $this->getDoctrine()->getManager();

        $cartService = $this->container->get(CartService::class);
        $cart = $cartService->getCurrentCart($this->getUser());
        $cart->checkCartIntegrity();

        $catalog = $em->getRepository('PromotionBundle:Catalog')->find($itemId);

        if ($catalog) {
            $commandeCatalog = null;
            if ($cart->getCommandeCatalogs()) {
                foreach ($cart->getCommandeCatalogs() as $commandeCatalogItem) {
                    if ($commandeCatalogItem->getCatalog() == $catalog) {
                        $commandeCatalog = $commandeCatalogItem;
                    }
                }
            }
            if ($commandeCatalog != null) {
                if ($commandeCatalog->getQuantity() > 1) {
                    $commandeCatalog->setQuantity($commandeCatalog->getQuantity() - 1);
                } else {
                    $cart->removeCommandeCatalog($commandeCatalog);
                    $em->remove($commandeCatalog);
                }
            }

            $em->persist($cart);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('financial_cart_index'));
    }

    /**
     * Calcul du prix panier
     * @param $bp
     * @param $session
     * @param $em
     * @return mixed
     */
    private function __calculPrixPanier($bp, $session, $em, $sum, $catalog) {
        // Initialisation des valeurs
        $tauxTva = 0;
        $tva = 0;
        $base_calcul = 0;
        $totalItem = 0;
        $panier = $session->get('panier');

        // Produit catalogue TTC
        $base_calcul = $this->manageCatalog($session, $bp, $base_calcul, $em, $sum, $catalog);
        $tva = $this->getMontantTva($base_calcul, $tauxTva);
        $articles_catalog = $this->getMontantTtc($base_calcul, $tva); // Montant total des produits catalogue
        // Bp TTC
        $bp_price = $bp->getPrice();

        // Promotion
        $coupon = null;
        if ($session->has('code_promo') and is_object($session->get('code_promo'))) {
            $couponSession = $session->get('code_promo');
            $coupon = $em->getRepository('PromotionBundle:Coupon')->find($couponSession->getId());
            if ($coupon->isDiscountOnlyBp()) {
                // TODO : Trouver une methode pour ne pas dupliquer encore du code pour que la reduc fonctionne
                $bp_price = $this->checkPromotionAction($coupon, $bp_price, $session);
            }
        }

        $tva += $this->getMontantTva($bp_price, $tauxTva);
        $bp_price += $tva;

        // Montant total de l'operation
        $total_panier = $articles_catalog + $bp_price;

        // Promotion sur la totalité
        if ($session->has('code_promo') and is_object($session->get('code_promo'))) {
            if ($coupon && !$coupon->isDiscountOnlyBp()) {
                // $coupon = $session->get('code_promo');
                $total_panier = $this->checkPromotionAction($coupon, $total_panier, $session);
            }
        }

        //die('finale');
        // Mise en session du total de la commande
        $this->updateSession($panier, 'total', $total_panier, 'panier', $session);

        return $total_panier;
    }

    public function checkPromotionAction($coupon, $total_bp, $session) {
        $totalHT = $total_bp;
        $totalTVA = 0;
        $total_cart = 0;
        $action = $coupon->getAction();
        $rule = $coupon->getRule();
        $command_qty = 0;
        $command_total_amount = 0;
        $tab = 'code_promo';
        $msgAmount = 'Promotion non applicable : le montant de la commande doit être au moins de ' . $rule->getConfiguration() . '€';
        $msgDate = 'Promotion non applicable : la date du coupon n\'est pas valide';

        // Verification des dates
        if (!DateEligibilityChecker::isEligible($coupon)) {
            $this->msgSession($session, $tab, $msgDate);
            return $totalHT;
        }

        // Verification du montant
        if (!AmountTotalChecker::isEligible($totalHT, $coupon)) {
            $this->msgSession($session, $tab, $msgAmount);
            return $totalHT;
        }

        // Calcul du montant de la commande avec promotion
        $totalHT = $this->calculPromo($totalHT, $action->getConfiguration(), $action->getType());
        return $totalHT;
    }

    /**
     * Gestion des messages d'erreurs lors des contrôles des règles pour la validation de la promotion
     * @param $session
     * @param $element
     * @param $msg
     */
    private function __msgSession($session, $element, $msg) {
        if ($session->has($element)) {
            $session->set($element, $msg);
        }

    }

    private function __calculPromo($total, $applicator, $action) {
        if ($action == "Order_fixed_discount") {
            return $total - $applicator;
        }

        if ($action == "Order_percentage_discount") {
            return $total * (1 - ($applicator / 100));
        }

    }

}
