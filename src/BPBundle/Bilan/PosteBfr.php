<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class PosteBfr {

    protected $detailPoste;
    protected $sommeValeur;

    public function __construct() {
        $this->detailPoste = new ArrayCollection();
        $this->sommeValeur = 0;
    }

    /**
     * @return mixed
     */
    public function getDetailPoste() {
        return $this->detailPoste;
    }

    public function getSommeValeur() {
        return $this->sommeValeur;
    }

    public function getSommeValeurCharge($chargeId) {
        $sommeValeurCharge = 0;
        foreach ($this->detailPoste as $currentDetail) {
            if ($chargeId == $currentDetail->getCharge()->getId()) {
                $sommeValeurCharge += $currentDetail->getValeur();
            }
        }
        return $sommeValeurCharge;
    }

    /**
     * @param mixed $detailPoste
     */
    public function addDetailPoste(PosteProduitInterface $detailPoste) {
        if ($this->detailPosteNotContainProduct($detailPoste)) {
            $this->detailPoste->add($detailPoste);
        }
        $this->sommeValeur += $detailPoste->getValeur();
    }

    public function addDetailPosteCharge(PosteChargeInterface $detailPoste) {
        if ($this->detailPosteNotContainCharge($detailPoste)) {
            $this->detailPoste->add($detailPoste);
        }
        $this->sommeValeur += $detailPoste->getValeur();
    }

    private function detailPosteNotContainProduct(PosteProduitInterface $detailPoste) {
        foreach ($this->detailPoste as $currentDetail) {
            if ($detailPoste->getProduit() && $detailPoste->getProduit()->getId() == $currentDetail->getProduit()->getId()) {
                $currentDetail->setValeur($currentDetail->getValeur() + $detailPoste->getValeur());
                return false;
            }
        }
        return true;
    }

    private function detailPosteNotContainCharge(PosteChargeInterface $detailPoste) {
        foreach ($this->detailPoste as $currentDetail) {
            if ($detailPoste->getCharge()->getId() == $currentDetail->getCharge()->getId()) {
                $currentDetail->setValeur($currentDetail->getValeur() + $detailPoste->getValeur());
                return false;
            }
        }
        return true;
    }

}
