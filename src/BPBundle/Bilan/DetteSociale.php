<?php

namespace BPBundle\Bilan;


use Doctrine\Common\Collections\ArrayCollection;

class DetteSociale {

    protected $detailDetteSociale;

    protected $sommeValeur;

    public function __construct() {
        $this->detailDetteSociale = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getDetailDetteSociale()
    {
        return $this->detailDetteSociale;
    }

    /**
     * @param mixed $detailDetteSociale
     */
    public function addDetailDetteSociale($detailDetteSociale)
    {
        $this->detailDetteSociale[] = $detailDetteSociale;

        return $this;
    }

    /**
     * @param mixed $detailDetteSociale
     */
    public function setDetailDetteSociale($detailDetteSociale)
    {
        $this->detailDetteSociale = $detailDetteSociale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSommeValeur()
    {
        return $this->sommeValeur;
    }

    /**
     * @param mixed $sommeValeur
     */
    public function setSommeValeur($sommeValeur)
    {
        $this->sommeValeur = $sommeValeur;

        return $this;
    }
}