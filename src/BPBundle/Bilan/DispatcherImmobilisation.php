<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class DispatcherImmobilisation {

    protected $investissements;

    protected $dateDuMois;

    protected $immobilisationIncorporelle;

    protected $immobilisationCorporelle;

    protected $immobilisationFinanciere;

    /**
     * Bilan constructor.
     * @param $investissements
     */
    public function __construct($investissements, $dateDuMois)
    {
        $this->investissements = $investissements;
        $this->dateDuMois = $dateDuMois;
        $this->immobilisationIncorporelle = new ArrayCollection();
        $this->immobilisationCorporelle = new ArrayCollection();
        $this->immobilisationFinanciere = new ArrayCollection();

        $this->init();
    }

    private function init() {
        // Répartition des investissements selon les classements des immobilisations
        foreach($this->investissements as $investissement) {
            switch($investissement->getChargeLabel()->getClassement()) {
                case 'incorporel' :
                    $this->immobilisationIncorporelle->add($investissement);
                    break;
                case 'corporel' :
                    $this->immobilisationCorporelle->add($investissement);
                    break;
                case 'financier' :
                    $this->immobilisationFinanciere->add($investissement);
                    break;
            }
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getImmobilisationIncorporelle()
    {
        return $this->immobilisationIncorporelle;
    }

    /**
     * @param ArrayCollection $immobilisationIncorporelle
     */
    public function setImmobilisationIncorporelle($immobilisationIncorporelle)
    {
        $this->immobilisationIncorporelle = $immobilisationIncorporelle;
    }

    /**
     * @return ArrayCollection
     */
    public function getImmobilisationCorporelle()
    {
        return $this->immobilisationCorporelle;
    }

    /**
     * @param ArrayCollection $immobilisationCorporelle
     */
    public function setImmobilisationCorporelle($immobilisationCorporelle)
    {
        $this->immobilisationCorporelle = $immobilisationCorporelle;
    }

    /**
     * @return ArrayCollection
     */
    public function getImmobilisationFinanciere()
    {
        return $this->immobilisationFinanciere;
    }

    /**
     * @param ArrayCollection $immobilisationFinanciere
     */
    public function setImmobilisationFinanciere($immobilisationFinanciere)
    {
        $this->immobilisationFinanciere = $immobilisationFinanciere;
    }
}