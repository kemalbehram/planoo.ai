<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoProduct;
use BPBundle\Pnl\Helper;

class FournisseurProduit implements PosteProduitInterface {

    protected $infoProduit;
    protected $delaiFournisseur;
    protected $dateDuMois;
    protected $dateFinActivite;
    protected $valeurFournisseur;
    protected $tousLesExercices;
    protected $tva;
    protected $stockOrganiserParDate;

    /**
     * StockProduit constructor.
     * @param $infoProduit
     */
    public function __construct(InfoProduct $infoProduit, $delaiFournisseur, $dateDuMois, $dateFinActivite, $tva, $tousLesExercices, $stockOrganiserParDate) {
        $this->infoProduit = $infoProduit;
        $this->delaiFournisseur = $delaiFournisseur;
        $this->dateDuMois = $dateDuMois;
        $this->dateFinActivite = $dateFinActivite;
        $this->tousLesExercices = $tousLesExercices;
        $this->stockOrganiserParDate = $stockOrganiserParDate;

        $this->tva = $infoProduit->getProduit()->getTvaAchats() !== null ? $infoProduit->getProduit()->getTvaAchats() : $tva;

        $this->init();
    }

    private function init() {
        $dateValeurFournisseur = clone $this->dateDuMois;
        $valeurClient = 0;
        if ($this->delaiFournisseur > 0) {
            $coutMensuelProduit = $this->getCoutMensuelParProduit();
            $valeurClient = $this->calculFournisseurProduit($coutMensuelProduit, $this->delaiFournisseur, $dateValeurFournisseur, $valeurClient, $this->stockOrganiserParDate);
        }
        $this->valeurFournisseur = $valeurClient;
    }

    private function calculFournisseurProduit($coutMensuelProduit, $delaiFournisseur, $date, $valeurFournisseur, $stockOrganiserParDate) {
        $nbJourDuMois = Helper::nbDayInMonth($date);
        $nbJourRestant = $delaiFournisseur - $nbJourDuMois;
        $coutMensuel = $this->getCoutMensuelByDate($coutMensuelProduit, $date);

        $dateStockMoisMoins1 = clone $date;
        $dateStockMoisMoins1->sub(new \DateInterval('P1M'));

        //récupération du stock du produit
        $stock = null;
        if (array_key_exists($date->format('Y-m-d'), $stockOrganiserParDate)) {
            $stockGlobal = $stockOrganiserParDate[$date->format('Y-m-d')];

            if ($stockGlobal) {
                foreach ($stockGlobal->getDetailPoste() as $stockElement) {
                    if ($stockElement->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                        $stock = $stockElement;
                    }
                }
            }
        }

        $stockMoisPrecedent = null;
        if (array_key_exists($dateStockMoisMoins1->format('Y-m-d'), $stockOrganiserParDate)) {
            $stockMoisPrecedentGlobal = $stockOrganiserParDate[$dateStockMoisMoins1->format('Y-m-d')];
            if ($stockGlobal) {
                foreach ($stockMoisPrecedentGlobal->getDetailPoste() as $stockElement) {
                    if ($stockElement->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                        $stockMoisPrecedent = $stockElement;
                    }
                }
            }
        }

        $assiette = 0;
        if ($stockMoisPrecedent) {
            $assiette -= $stockMoisPrecedent->getValeur();
        }

        $assiette += $coutMensuel;

        if ($stock) {
            $assiette += $stock->getValeur();
        }

        if ($nbJourRestant > 0) {
            $valeurFournisseur += $assiette * (1 + ($this->tva / 100));
            $date->sub(new \DateInterval('P1M'));
            return $this->calculFournisseurProduit($coutMensuelProduit, $nbJourRestant, $date, $valeurFournisseur, $stockOrganiserParDate);
        } else {
            $valeurFournisseur += (($delaiFournisseur / $nbJourDuMois) * ($assiette) * (1 + ($this->tva / 100)));
        }

        return $valeurFournisseur * -1;
    }

    public function getProduit() {
        return $this->infoProduit;
    }

    public function getValeur() {
        return $this->valeurFournisseur;
    }

    public function setValeur($valeur) {
        $this->valeurFournisseur = $valeur;

        return $this;
    }

    public function getDateDuMois() {
        return $this->dateDuMois;
    }

    /**
     * @return array
     */
    private function getCoutMensuelParProduit() {
        $coutMensuel = [];
        foreach ($this->tousLesExercices as $exercice) {
            foreach ($exercice->getInfoProduct() as $infoProduct) {
                if ($infoProduct->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                    $coutMensuel = array_merge($coutMensuel, $infoProduct->getCoutMensuel());
                }
            }
        }

        return $coutMensuel;
    }

    private function getCoutMensuelByDate($caMensuelProduit, \DateTime $date) {
        $valeur = 0;

        $keyFormat = $date->format('Y-m-d');
        if (array_key_exists($keyFormat, $caMensuelProduit)) {
            $valeur = $caMensuelProduit[$keyFormat];
        }

        return $valeur;
    }

}
