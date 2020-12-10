<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 16:07
 */

namespace PromotionBundle\Checker;


use PromotionBundle\Entity\Coupon;

class UsageLimityChecker
{
    /**
     * Usage
     */
    public static function isEligible(Coupon $coupon)
    {
        if (null === $usageLimit = $coupon->getUsageLimit()) {
            return true;
        }

        if ($coupon->getUsed() < $usageLimit) {
            return true;
        }

        return false;
    }

}