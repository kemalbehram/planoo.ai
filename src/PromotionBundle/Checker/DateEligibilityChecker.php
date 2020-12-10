<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 15:43
 */

namespace PromotionBundle\Checker;


class DateEligibilityChecker
{
    /**
     * Date
     */
    public static function isEligible($coupon)
    {
        $now = new \DateTime();

        $startsAt = $coupon->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $coupon->getEndsAt();
        if (null !== $endsAt && $now > $endsAt) {
            return false;
        }

        return true;
    }
}