<?php

namespace PromotionBundle\Service;

use Doctrine\ORM\EntityManager;

class CatalogService extends \Twig_Extension {
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getFunctions() {
        return array(new \Twig_SimpleFunction('getNextFormula', array($this, 'getNextFormula')));
    }

    public function getNextFormula($businessPlan) {
        $catalogUpgrade = $this->em->getRepository('PromotionBundle:Catalog')->getNextCatalogUpgrade($businessPlan->getCatalog());
        if ($catalogUpgrade) {
            return $catalogUpgrade->getCatalogDestination();
        }
        return null;
    }

}