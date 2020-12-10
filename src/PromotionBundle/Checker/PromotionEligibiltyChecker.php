<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 17/11/16
 * Time: 16:00
 */

namespace PromotionBundle\Checker;


class PromotionEligibiltyChecker
{

    protected $datesEligibilityChecker;


    protected $usageLimitEligibilityChecker;


    protected $couponsEligibilityChecker;


    protected $rulesEligibilityChecker;


    public function __construct(
        $datesEligibilityChecker, $usageLimitEligibilityChecker, $amountTotalChecker, $rulesEligibilityChecker
    ) {
        $this->datesEligibilityChecker = $datesEligibilityChecker;
        $this->usageLimitEligibilityChecker = $usageLimitEligibilityChecker;
        $this->amountTotalChecker = $amountTotalChecker;
        $this->rulesEligibilityChecker = $rulesEligibilityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible($subject, Promotion $promotion)
    {
        if (!$this->datesEligibilityChecker->isEligible($promotion)) {
            return false;
        }else{
            return $this->datesEligibilityChecker->isEligible($promotion);
        }

        if (!$this->usageLimitEligibilityChecker->isEligible($promotion)) {
            return false;
        }else{
            return $this->usageLimitEligibilityChecker->isEligible($promotion);
        }

        if (!$this->amountTotalChecker->isEligible($promotion)) {
            return false;
        }else{
            return $this->amountTotalChecker->isEligible($promotion);
        }

        $eligible = $this->rulesEligibilityChecker->isEligible($subject, $promotion);
        if (!$eligible) {
            return false;
        }


        return $this->couponsEligibilityChecker->isEligible($subject, $promotion);
    }
}

