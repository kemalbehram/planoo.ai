<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 15:36
 */

namespace PromotionBundle\Checker;


class CartQteRuleChecker
{

    /**
     *   Quantité
     */
    public static function isEligible($qte_facture, $coupon)
    {
        $qte = $coupon->getRegle()->getConfig();
        return $qte_facture >= $qte;
    }
}
