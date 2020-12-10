<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Amortissement;

class Immobilisation {

    protected $investissement;

    protected $duree;

    protected $valeurNette;

    protected $amortissement;

    /**
     * Immobilisation constructor.
     * @param $investissement
     */
    public function __construct($investissement, $duree)
    {
        $this->investissement = $investissement;
        $this->duree = $duree;

        $this->init();
    }

    private function init() {
        $this->amortissement = Amortissement::calculAmortissement($this->investissement->getAmount(), $this->duree);
        $baseAmortissement = $this->investissement->getAmount();
        if ($this->investissement->getBaseAmortissement()) {
            $baseAmortissement = $this->investissement->getBaseAmortissement();
        }
        $this->valeurNette = $baseAmortissement - $this->amortissement;
        // Pour des raisons d'arrondit (14 chiffres après la virgule), la valeur nette peut être négative pour le dernier mois
        if ($this->valeurNette < 0) {
            $this->valeurNette = 0;
        }
        $this->investissement->setBaseAmortissement($this->valeurNette);
    }

    public function getValeurNette() {
        return $this->valeurNette;
    }
}