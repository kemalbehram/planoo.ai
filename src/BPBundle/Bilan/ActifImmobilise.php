<?php

namespace BPBundle\Bilan;


use Doctrine\Common\Collections\ArrayCollection;

class ActifImmobilise {

    protected $immobilisationIncorporelle;

    protected $immobilisationCorporelle;

    protected $immobilisationFinanciere;

    protected $totalActifImmobilise;

    /**
     * ActifImmobilise constructor.
     */
    public function __construct()
    {
        $this->totalActifImmobilise = 0;
    }


    /**
     * @return mixed
     */
    public function getImmobilisationIncorporelle()
    {
        return $this->immobilisationIncorporelle;
    }

    /**
     * @param mixed $immobilisationIncorporelle
     */
    public function setImmobilisationIncorporelle($immobilisationIncorporelle)
    {
        $this->immobilisationIncorporelle = $immobilisationIncorporelle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImmobilisationCorporelle()
    {
        return $this->immobilisationCorporelle;
    }

    /**
     * @param mixed $immobilisationCorporelle
     */
    public function setImmobilisationCorporelle($immobilisationCorporelle)
    {
        $this->immobilisationCorporelle = $immobilisationCorporelle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImmobilisationFinanciere()
    {
        return $this->immobilisationFinanciere;
    }

    /**
     * @param mixed $immobilisationFinanciere
     */
    public function setImmobilisationFinanciere($immobilisationFinanciere)
    {
        $this->immobilisationFinanciere = $immobilisationFinanciere;

        return $this;
    }

    public function calculTotalActifImmobilise() {
        $this->totalActifImmobilise = $this->immobilisationIncorporelle->getMontantTotal() +
        $this->immobilisationCorporelle->getMontantTotal() +
        $this->immobilisationFinanciere->getMontantTotal();
    }

    /**
     * @return int
     */
    public function getTotalActifImmobilise()
    {
        return $this->totalActifImmobilise;
    }

}