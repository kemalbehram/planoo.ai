<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoProduct;
use BPBundle\Pnl\Helper;

class AutreCreanceProduit implements PosteProduitInterface {

    protected $infoProduit;
    protected $acompte;
    protected $dateDuMois;
    protected $valeur;
    protected $tva;
    protected $tvaSurEncaissement;
    protected $delaiPaiementFournisseur;
    protected $stockOrganiserParDate;
    protected $tousLesExercices;

    /**
     * StockProduit constructor.
     * @param $infoProduit
     */
    public function __construct(InfoProduct $infoProduit, $acompte, $delaiPaiementFournisseur, $dateDuMois, $tva, $tvaSurEncaissement, $stockOrganiserParDate, $tousLesExercices, $fournisseurOrganiserParDate) {
        $this->infoProduit = $infoProduit;
        $this->acompte = $acompte;
        $this->dateDuMois = $dateDuMois;
        $this->tva = $infoProduit->getProduit()->getTvaAchats() !== null ? $infoProduit->getProduit()->getTvaAchats() : $tva;
        $this->tvaSurEncaissement = $tvaSurEncaissement;
        $this->delaiPaiementFournisseur = $delaiPaiementFournisseur;
        $this->stockOrganiserParDate = $stockOrganiserParDate;
        $this->tousLesExercices = $tousLesExercices;
        $this->fournisseurOrganiserParDate = $fournisseurOrganiserParDate;

        $this->init();
    }

    private function init() {
        $this->initAcompte();
        $this->initTVAProduit();
    }

    private function initTVAProduit() {

        $dateMMoins1 = clone $this->dateDuMois;
        $dateMMoins1->sub(new \DateInterval('P1M'));

        //calcul assiette TVA payee en M (declaré en M à ajouter au creance/dette de M)
        $tvaSurAchatEnCours = $this->getAssietteTVA($this->dateDuMois) * ($this->tva / 100);
        //calcul assiette TVA M-1 (déclaré en M-1 a Payer en M) (A payer)
        $tvaSurAchatMoisMoins1 = $this->getAssietteTVA($dateMMoins1) * ($this->tva / 100);

        if ($this->tvaSurEncaissement) {
            $dateMMoins2 = clone $dateMMoins1;
            $dateMMoins2->sub(new \DateInterval('P1M'));

            $detteFournisseurMMoins1 = $this->getDetteFournisseurByDate($dateMMoins1);
            $detteFournisseurMMoins2 = $this->getDetteFournisseurByDate($dateMMoins2);

            $stockMMoins1 = $this->getStockByDate($dateMMoins1);
            $stockMMoins2 = $this->getStockByDate($dateMMoins2);

            $coutMensuelMMoins1 = 0;
            $keyFormat = $dateMMoins1->format('Y-m-01');
            $tousLesCoutMensuelProduit = $this->getCoutMensuelParProduit();
            if (array_key_exists($keyFormat, $tousLesCoutMensuelProduit)) {
                $coutMensuelMMoins1 = $tousLesCoutMensuelProduit[$keyFormat];
            }

            $tvaDecaissementMMoins1 = ($coutMensuelMMoins1 - ($stockMMoins2 - $stockMMoins1) - (($detteFournisseurMMoins2 - $detteFournisseurMMoins1) / (1 + $this->tva / 100))) * ($this->tva / 100);

            $this->valeur = ($tvaSurAchatEnCours - $tvaDecaissementMMoins1);
        } else {
            $this->valeur += ($tvaSurAchatEnCours - $tvaSurAchatMoisMoins1);
        }
    }

    private function initAcompte() {
        $coutMensuel = 0;
        $tousLesCoutMensuelProduit = $this->infoProduit->getCoutMensuel();
        $keyFormat = $this->dateDuMois->format('Y-m-d');
        if (array_key_exists($keyFormat, $tousLesCoutMensuelProduit)) {
            $coutMensuel = $tousLesCoutMensuelProduit[$keyFormat];
        }
        $acompteSurAchat = ($coutMensuel * ($this->acompte / 100) * ($this->tva / 100));
        $this->valeur += $acompteSurAchat;
    }

    public function getProduit() {
        return $this->infoProduit;
    }

    public function getValeur() {
        return $this->valeur;
    }

    public function setValeur($valeur) {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDateDuMois() {
        return $this->dateDuMois;
    }

    public function getAssietteTVA($date) {

        $coutMensuel = 0;
        $tousLesCoutMensuelProduit = $this->getCoutMensuelParProduit();
        $keyFormat = $date->format('Y-m-01');
        if (array_key_exists($keyFormat, $tousLesCoutMensuelProduit)) {
            $coutMensuel = $tousLesCoutMensuelProduit[$keyFormat];
        }

        $stock = null;
        if (array_key_exists($date->format('Y-m-d'), $this->stockOrganiserParDate)) {
            $stockGlobal = $this->stockOrganiserParDate[$date->format('Y-m-d')];

            if ($stockGlobal) {
                foreach ($stockGlobal->getDetailPoste() as $stockElement) {
                    if ($stockElement->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                        $stock = $stockElement;
                    }
                }
            }
        }

        $dateStockMoisMoins1 = clone $date;
        $dateStockMoisMoins1->sub(new \DateInterval('P1M'));

        $stockMoisPrecedent = null;
        if (array_key_exists($dateStockMoisMoins1->format('Y-m-d'), $this->stockOrganiserParDate)) {
            $stockMoisPrecedentGlobal = $this->stockOrganiserParDate[$dateStockMoisMoins1->format('Y-m-d')];
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

        return($assiette);
    }

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

    private function getDetteFournisseurByDate($date) {
        $detteFournisseur = 0;

        $key = $date->format('Y-m-d');

        if (array_key_exists($key, $this->fournisseurOrganiserParDate)) {
            $elementBfr = $this->fournisseurOrganiserParDate[$key];
            foreach ($elementBfr->getDetailPoste() as $fournisseurProduit) {
                //instance of ClientProduit
                if ($fournisseurProduit->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                    $detteFournisseur = $fournisseurProduit->getValeur();
                }
            }
        }
        return $detteFournisseur;
    }

    private function getStockByDate($date) {
        $stockValeur = 0;

        $key = $date->format('Y-m-d');

        if (array_key_exists($key, $this->stockOrganiserParDate)) {
            $elementBfr = $this->stockOrganiserParDate[$key];
            foreach ($elementBfr->getDetailPoste() as $stock) {
                //instance of ClientProduit
                if ($stock->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                    $stockValeur = $stock->getValeur();
                }
            }
        }
        return $stockValeur;
    }

}
