<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;

class DispatchDetteFiscale {

    protected $infoBpMensuel;

    /**
     * Stock constructor.
     * @param $infoProduits
     */
    public function __construct($infoBpMensuel) {
        $this->infoBpMensuel = $infoBpMensuel;
    }

    public function getValeurBfrDetteFiscale() {
        $tabDetailDetteFiscaleParDate = [];
        foreach ($this->infoBpMensuel as $bpMensuel) {
            $dateValeurFournisseur = clone $bpMensuel->getDate();
            $key = $dateValeurFournisseur->format('Y-m-d');
            $tabDetailDetteFiscaleParDate[$key] = $bpMensuel->getImpotEtTaxeMensuel();
        }

        return $tabDetailDetteFiscaleParDate;
    }

}
