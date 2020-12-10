<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Rate;

class ImpotEtTaxe {

    protected $tauxChargesFiscales;

    protected $sommeCaMensuel;

    protected $sommeSalaireMensuel;

    protected $sommeImpotEtTaxeMensuel;

    /**
     * ImpotEtTaxe constructor.
     * @param $tauxChargesFiscales
     * @param $sommeCaMensuel
     * @param $sommeSalaireMensuel
     */
    public function __construct($tauxChargesFiscales, $sommeCaMensuel, $sommeSalaireMensuel)
    {
        $this->tauxChargesFiscales = $tauxChargesFiscales;
        $this->sommeCaMensuel = $sommeCaMensuel;
        $this->sommeSalaireMensuel = $sommeSalaireMensuel;

        $this->init();
    }

    public function getSommeImpotEtTaxeMensuel() {
        return $this->sommeImpotEtTaxeMensuel * -1;
    }

    private function init() {
        $this->sommeImpotEtTaxeMensuel = 0;
        foreach($this->tauxChargesFiscales as $tauxCharge) {
            switch($tauxCharge->getType()->getId()) {
                // Sur le CA
                case 1:
                    $this->sommeImpotEtTaxeMensuel += $this->calculSommeImpotEtTaxeMensuelle($this->sommeCaMensuel, $tauxCharge);
                    break;
                // Sur les salaires
                case 2:
                    $this->sommeImpotEtTaxeMensuel += $this->calculSommeImpotEtTaxeMensuelle($this->sommeSalaireMensuel, $tauxCharge);
                    break;
            }
        }
    }

    private function calculSommeImpotEtTaxeMensuelle($somme, Rate $tauxCharge) {
        return $somme * ($tauxCharge->getValue()/100);
    }
}