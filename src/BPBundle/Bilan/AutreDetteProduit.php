<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoProduct;

class AutreDetteProduit implements PosteProduitInterface {

    protected $infoProduit;
    protected $acompte;
    protected $dateDuMois;
    protected $valeur;
    protected $tva;
    protected $tvaSurEncaissement;
    protected $delaiPaiementClient;
    protected $tousLesExercices;
    protected $tabDetailElementParDate;

    /**
     * StockProduit constructor.
     * @param $infoProduit
     */
    public function __construct(InfoProduct $infoProduit, $acompte, $delaiPaiementClient, $dateDuMois, $tva, $tvaSurEncaissement, $tousLesExercices, $tabDetailElementParDate) {
        $this->infoProduit = $infoProduit;
        $this->acompte = $acompte;
        $this->dateDuMois = $dateDuMois;
        $this->tva = $infoProduit->getProduit()->getTvaVentes() !== null ? $infoProduit->getProduit()->getTvaVentes() : $tva;
        $this->tvaSurEncaissement = $tvaSurEncaissement;
        $this->delaiPaiementClient = $delaiPaiementClient;
        $this->tousLesExercices = $tousLesExercices;
        $this->tabDetailElementParDate = $tabDetailElementParDate;

        $this->init();
    }

    private function init() {

        $this->dateDuMois = clone $this->dateDuMois;

        $this->initAcompte();
        $this->initTVA();
    }

    private function initTVA() {

        $dateMMoins1 = clone $this->dateDuMois;
        $dateMMoins1->sub(new \DateInterval('P1M'));
        $caMensuelEncours = 0;
        $caMensuelMMoins1 = 0;

        $tousLesCaMensuelProduit = $this->getCaMensuelParProduit();

        $keyFormat = $this->dateDuMois->format('Y-m-d');
        if (array_key_exists($keyFormat, $tousLesCaMensuelProduit)) {
            $caMensuelEncours = $tousLesCaMensuelProduit[$keyFormat];
        }


        $keyFormat = $dateMMoins1->format('Y-m-01');
        if (array_key_exists($keyFormat, $tousLesCaMensuelProduit)) {
            $caMensuelMMoins1 = $tousLesCaMensuelProduit[$keyFormat];
        }

        $tvaSurVenteMoisEnCours = ($caMensuelEncours * ($this->tva / 100)) * -1;
        $tvaSurVenteMoisMoins1 = ($caMensuelMMoins1 * ($this->tva / 100)) * -1; //is paid this mounth

        if ($this->tvaSurEncaissement) {

            $dateMMoins2 = clone $dateMMoins1;
            $dateMMoins2->sub(new \DateInterval('P1M'));

            $creanceClient = $this->getCreanceClientByDate($dateMMoins1);
            $creanceClientMMoins1 = $this->getCreanceClientByDate($dateMMoins2);

            $tvaEncaissementMMoins1 = ($caMensuelMMoins1 + (($creanceClientMMoins1 - $creanceClient) / (1 + $this->tva / 100))) * ($this->tva / 100) * -1;

            $this->valeur = ($tvaSurVenteMoisEnCours - $tvaEncaissementMMoins1);
        } else {

            $this->valeur += ($tvaSurVenteMoisEnCours - $tvaSurVenteMoisMoins1);
        }
    }

    private function initAcompte() {
        $tousLesCaMensuelProduit = $this->infoProduit->getCaMensuel();
        $keyFormat = $this->dateDuMois->format('Y-m-d');
        if (array_key_exists($keyFormat, $tousLesCaMensuelProduit)) {
            $caMensuel = $tousLesCaMensuelProduit[$keyFormat];
        }

        $acompteSurVente = ($caMensuel * ($this->acompte / 100) * ($this->tva / 100) ) * - 1;
        $this->valeur += $acompteSurVente;
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

    private function getCaMensuelParProduit() {
        $caMensuel = [];
        foreach ($this->tousLesExercices as $exercice) {
            foreach ($exercice->getInfoProduct() as $infoProduct) {
                if ($infoProduct->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                    $caMensuel = array_merge($caMensuel, $infoProduct->getCaMensuel());
                }
            }
        }

        return $caMensuel;
    }

    private function getCreanceClientByDate($date) {
        $creanceClient = 0;

        $key = $date->format('Y-m-d');

        if (array_key_exists($key, $this->tabDetailElementParDate)) {
            $elementBfr = $this->tabDetailElementParDate[$key];
            if ($elementBfr instanceof Client) {
                foreach ($elementBfr->getDetailPoste() as $clientProduit) {
                    //instance of ClientProduit
                    if ($clientProduit->getProduit()->getProduit()->getId() == $this->infoProduit->getProduit()->getId()) {
                        $creanceClient = $clientProduit->getValeur();
                    }
                }
            }
        }
        return $creanceClient;
    }

}
