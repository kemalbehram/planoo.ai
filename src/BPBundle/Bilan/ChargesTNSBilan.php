<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\Exercice;
use BPBundle\Pnl\Helper;

class ChargesTNSBilan {

    protected $exercice;
    protected $exercice_N_Moins_1;
    protected $exercice_N_Moins_2;
    protected $dateDuMois;
    protected $paiement;
    protected $dette;
    protected $detteFinale = 0;
    protected $chargesTNSMensuellesPnl;

    /**
     * IsBilan constructor.
     * @param $exercice
     * @param $exercicePrecedent
     */
    public function __construct(Exercice $exercice, $exercice_N_Moins_1, $exercice_N_Moins_2, $dateDuMois, $chargesTNSMensuellesPnl) {
        $this->exercice = $exercice;
        $this->exercice_N_Moins_1 = $exercice_N_Moins_1;
        $this->exercice_N_Moins_2 = $exercice_N_Moins_2;
        $this->dateDuMois = $dateDuMois;
        $this->chargesTNSMensuellesPnl = $chargesTNSMensuellesPnl;

        $this->exercice->setCumuleChargesTNSPnl($this->exercice->getCumuleChargesTNSPnl() + $this->chargesTNSMensuellesPnl);

        $this->init();
    }

    /*
     * Fev = cotisation Nx-2 /4 
     * Mai = cotisation Nx-2 /4
     * Aout= cotisation Nx-1/4+[(cotisation Nx-1 - cotisation Nx-2)/2] 
     * Nov= cotisation Nx-1/4+[(cotisation Nx-1 - cotisation Nx-2)/2]
     */

    private function init() {
        $moisAcompte = [2, 5];
        $moisAcompteEtRegul = [8, 11];
        $cotisationNMoins2 = 0;
        $cotisationNMoins1 = 0;


        if ($this->exercice_N_Moins_1 != null && $this->exercice_N_Moins_1->getChargesTNS() != 0) {
            $cotisationNMoins1 = $this->exercice_N_Moins_1->getChargesTNS();
        }

        if ($this->exercice_N_Moins_2 != null && $this->exercice_N_Moins_2->getChargesTNS() != 0) {
            $cotisationNMoins2 = $this->exercice_N_Moins_2->getChargesTNS();
        }

        if (in_array($this->dateDuMois->format('m'), $moisAcompte)) {
            $this->paiement = $cotisationNMoins2 / 4;
            $this->paiement *= -1;
        }

        if (in_array($this->dateDuMois->format('m'), $moisAcompteEtRegul)) {
            $this->paiement = $cotisationNMoins1 / 4; //Acompte
            $this->paiement += ($cotisationNMoins1 - $cotisationNMoins2) / 2;
            $this->paiement *= -1;
        }
        if ($this->exercice_N_Moins_1 != null && $this->exercice_N_Moins_1->getChargesTNS() != 0) {
            $this->dette = $this->exercice_N_Moins_1->getChargesTNSApresAcompte() + $this->paiement;
            $this->exercice_N_Moins_1->setChargesTNSApresAcompte($this->dette);
        }

        $this->detteFinale = $this->dette + $this->exercice->getCumuleChargesTNSPnl();
    }

    /**
     * @return mixed
     */
    public function getChargesTNSMensuellesMensuelPnl() {
        return $this->chargesTNSMensuellesPnl;
    }

    /**
     * @return int
     */
    public function getDetteFinale() {
        return $this->detteFinale;
    }

}
