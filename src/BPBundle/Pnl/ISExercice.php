<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Rate;

class ISExercice {

    protected $tauxIS;
    protected $sommeRcaiNet;
    protected $sommeIsExercice;
    protected $caAnnuel;
    protected $isIr;

    /**
     * ISExercice constructor.
     * @param $tauxIS
     * @param $sommeCaExercice
     */
    public function __construct($tauxIS, $sommeRcaiNet, $caAnnuel, $isIr) {
        $this->tauxIS = $tauxIS;
        $this->sommeRcaiNet = $sommeRcaiNet;
        $this->caAnnuel = $caAnnuel;
        $this->isIr = $isIr;

        $this->init();
    }

    public function getSommeIsExercice() {
        return $this->sommeIsExercice;
    }

    private function init() {
        $sommeIsExercice = 0;

        if (!$this->isIr) {
            $trancheSuivante = $this->sommeRcaiNet;
            foreach ($this->tauxIS as $taux) {
                if (!($trancheSuivante > 7800000 && $taux->getId() == 3)) {
                    if ($this->sommeRcaiNet >= $taux->getBaseMin()) {
                        if ($this->conditionValid($taux)) {
                            $baseDeCalcul = $trancheSuivante;

                            // Calcul de la tranche suivante
                            if ($taux->getBaseMax() && $baseDeCalcul > $taux->getBaseMax()) {
                                $trancheSuivante = $baseDeCalcul - $taux->getBaseMax();
                                $baseDeCalcul = $taux->getBaseMax() - $taux->getBaseMin();
                            }
                            $sommeIsExercice += ($baseDeCalcul * ($taux->getValue() / 100)) * -1;
                        }
                    }
                }
            }
        }

        $this->sommeIsExercice = $sommeIsExercice;
    }

    /**
     * Permet de valider si le taux à une assiette et si oui est-ce qu'on peut en profiter
     */
    private function conditionValid(Rate $taux) {
        if (!$taux->getAssietteCa() || $taux->getAssietteCa() == 0) {
            return true;
        }

        // Le signe de l'assiette permet de connaitre le sens du test : inférieur ou supérieur
        $sens = $taux->getAssietteCa() > 0;
        switch ($sens) {
            case true:
                return $this->caAnnuel > $taux->getAssietteCa();
                break;
            case false:
                // On multiplie l'assiette par -1 afin d'effectuer le test sur un chiffre positif
                // Le signe "-" permettant simplément de connaître le signe du test : inférieur pour ce cas la
                return $this->caAnnuel < ($taux->getAssietteCa() * -1);
                break;
        }
    }

}
