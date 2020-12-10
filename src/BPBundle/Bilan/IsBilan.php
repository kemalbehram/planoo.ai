<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\Exercice;
use BPBundle\Pnl\Helper;

class IsBilan {

    protected $exercice;

    protected $exercice_N_Moins_1;

    protected $exercice_N_Moins_2;

    protected $dateDuMois;

    protected $acompte;

    protected $dette;

    protected $detteFinale = 0;

    protected $isMensuelPnl;

    /**
     * IsBilan constructor.
     * @param $exercice
     * @param $exercicePrecedent
     */
    public function __construct(Exercice $exercice, $exercice_N_Moins_1, $exercice_N_Moins_2, $dateDuMois, $isMensuelPnl)
    {
        $this->exercice = $exercice;
        $this->exercice_N_Moins_1 = $exercice_N_Moins_1;
        $this->exercice_N_Moins_2 = $exercice_N_Moins_2;
        $this->dateDuMois = $dateDuMois;
        $this->isMensuelPnl = $isMensuelPnl;

        $this->exercice->setCumuleIsPnl($this->exercice->getCumuleIsPnl() + $this->isMensuelPnl);

        $this->init();
    }

    private function init() {
        if ($this->exercice_N_Moins_1 != null && $this->exercice_N_Moins_1->getIs() != 0) {
            $acompteIs = ($this->exercice_N_Moins_1->getIs() / 4) * -1;
            $nbMoisEcartDette = [2,5,8,11];
            $nbMoisInterval = Helper::nbMonthDiff($this->exercice->getDateDebut(), $this->dateDuMois);

            $this->acompte = 0;
            if (in_array($nbMoisInterval, $nbMoisEcartDette)) {
                $this->acompte = $acompteIs;
            } elseif ($nbMoisInterval == 3) {
                $is_Exercice_N_Moins_2 = 0;
                if ($this->exercice_N_Moins_2 != null && $this->exercice_N_Moins_2->getIs() != 0) {
                    $is_Exercice_N_Moins_2 = $this->exercice_N_Moins_2->getIs();
                }
                // IS de l'exercice N-1   -   IS de l'exercice N-2
                $this->acompte = ($this->exercice_N_Moins_1->getIs() - $is_Exercice_N_Moins_2) * -1;
            }

            $this->dette = $this->exercice_N_Moins_1->getIsApresAcompte() + $this->acompte;
            $this->exercice_N_Moins_1->setIsApresAcompte($this->dette);
        } else {
            $this->acompte = 0;
            $this->dette = 0;
        }
        $this->detteFinale = $this->dette + $this->exercice->getCumuleIsPnl();
    }

    /**
     * @return mixed
     */
    public function getIsMensuelPnl()
    {
        return $this->isMensuelPnl;
    }

    /**
     * @return int
     */
    public function getDetteFinale()
    {
        return $this->detteFinale;
    }
}