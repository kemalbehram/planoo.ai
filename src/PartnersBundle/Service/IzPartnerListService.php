<?php

namespace PartnersBundle\Service;

class IzPartnerListService {

    private $em;
    private $partners;

    public function __construct($em) {

        $this->em = $em;

        $this->partners = $this->em->getRepository('PartnersBundle:Partner')->findAll();
    }

    public function getPartners() {
        return $this->partners;
    }

}
