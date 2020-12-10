<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PromotionBundle\Entity;

/**
 * Description of CouponKind
 *
 * @author franc
 */
abstract class CouponRange {

    const BP_ONLY = 'BP_ONLY';
    const CART = 'CART';
    const OPTIONS_ONLY = 'OPTIONS_ONLY';

}
