<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 15:36
 */

namespace PromotionBundle\Checker;


class AmountTotalChecker
{
    /**
     *   Montant
     */
    public static function isEligible($amount_facture, $coupon)
    {
        $amount = $coupon->getRule()->getConfiguration();

        return $amount_facture >= $amount;
    }
}
