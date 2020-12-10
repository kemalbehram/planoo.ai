<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Amortissement;
use BPBundle\Pnl\Helper;
use Doctrine\Common\Collections\ArrayCollection;

class CategorieImmobilisation {

    protected $immobilisations;

    protected $dateDuMois;

    protected $codeCountry;

    protected $montantTotal;

    protected $detailImmobilisation;

    /**
     * CategorieImmobilisation constructor.
     * @param $immobilisations
     * @param $date
     */
    public function __construct($immobilisations, $date, $codeCountry)
    {
        $this->immobilisations = $immobilisations;
        $this->dateDuMois = $date;
        $this->codeCountry = $codeCountry;
        $this->detailImmobilisation = new ArrayCollection();

        $this->init();
    }

    private function init() {
        $montantTotal = 0;
        foreach($this->immobilisations as $investissement) {
            $duree = Helper::getDureeAmortissementByInvestissement($investissement, $this->codeCountry);
            if (Amortissement::isValidAmortissement($investissement, $this->dateDuMois, $duree)) {
                $immoDetail = new Immobilisation($investissement, $duree);
                $this->detailImmobilisation->add($immoDetail);
                $montantTotal += $immoDetail->getValeurNette();
            }
        }

        $this->setMontantTotal($montantTotal);
    }

    /**
     * @return mixed
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * @param mixed $montantTotal
     */
    private function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;
    }
}