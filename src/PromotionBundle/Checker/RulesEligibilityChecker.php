<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 15:53
 */

namespace PromotionBundle\Checker;


use PromotionBundle\Entity\Coupon;
use PromotionBundle\Entity\Rule;

class RulesEligibilityChecker
{
    public function isEligible($subject, Coupon $coupon)
    {
        if (!$coupon->hasRules()) {
            return true;
        }

        foreach ($coupon->getRules() as $rule) {
            if (!$this->isEligibleToRule($subject, $rule)) {
                return false;
            }
        }

        return true;
    }


    protected function isEligibleToRule($subject, Rule $rule)
    {
        $checker = $this->get($rule->getType());

        return $checker->isEligible($subject, $rule->getConfig());
    }
}