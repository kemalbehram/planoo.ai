<?php

namespace BPBundle\Bilan;

class Fournisseur extends PosteBfr {

    protected $chargeAPayer;
    protected $valeur;
    protected $valeurFournisseurCharge;

    public function __construct() {
        parent::__construct();
        $this->chargeAPayer = false;
        $this->valeur = 0;
        $this->valeurFournisseurCharge = 0;
    }

    /**
     * @return mixed
     */
    public function getChargeAPayer() {
        return $this->chargeAPayer;
    }

    /**
     * @param mixed $chargeAPayer
     */
    public function setChargeAPayer($chargeAPayer) {
        $this->chargeAPayer = $chargeAPayer;

        return $this;
    }

    public function getValeurFournisseurCharge() {
        return $this->valeurFournisseurCharge;
    }

    public function setValeurFournisseurCharge($valeurFournisseurCharge) {
        $this->valeurFournisseurCharge = $valeurFournisseurCharge;
    }

}
