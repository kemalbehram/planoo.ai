<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;

class BfrProduit {
    protected $produit;

    protected $delai;

    protected $dateDuMois;

    protected $dateDebutActivite;

    protected $dateFinActivite;

    protected $valeur;

    /**
     * StockProduit constructor.
     * @param $produit
     */
    public function __construct(InfoProduct $produit, $delai, $dateDuMois, $dateDebutActivite, $dateFinActivite)
    {
        $this->produit = $produit;
        $this->delai = $delai;
        $this->dateDuMois = $dateDuMois;
        $this->dateDebutActivite = $dateDebutActivite;
        $this->dateFinActivite = $dateFinActivite;

        $this->init();
    }

    private function init() {
        $dateValeur = clone $this->dateDuMois;
        $valeur = 0;
        if ($this->delai > 0) {
            $valeur = $this->calculProduit($this->produit->getCaMensuel(), $this->delai, $dateValeur, $valeur);
            $this->dateDuMois = $dateValeur;
        }
        $this->valeur = $valeur;
    }

    public function getDateDuMois() {
        return $this->dateDuMois;
    }

    public function getValeur() {
        return $this->valeur;
    }

    public function setValeur($valeur) {
        $this->valeur = $valeur;

        return $this;
    }

    public function getProduit() {
        return $this->produit;
    }
}