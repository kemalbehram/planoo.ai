<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class Client extends PosteBfr {

    protected $produitARecevoir;

    public function __construct() {
        parent::__construct();
        $this->produitARecevoir = false;
    }

    /**
     * @return mixed
     */
    public function getProduitARecevoir()
    {
        return $this->produitARecevoir;
    }

    /**
     * @param mixed $produitARecevoir
     */
    public function setProduitARecevoir($produitARecevoir)
    {
        $this->produitARecevoir = $produitARecevoir;

        return $this;
    }
}