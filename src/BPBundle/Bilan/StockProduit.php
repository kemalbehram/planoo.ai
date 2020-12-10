<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoProduct;
use BPBundle\Pnl\Helper;

class StockProduit implements PosteProduitInterface {

    protected $produit;
    protected $delaiStock;
    protected $dateDuMois;
    protected $dateFinActivite;
    protected $valeurStock;
    protected $tousLesExercices;

    /**
     * StockProduit constructor.
     * @param $produit
     */
    public function __construct(InfoProduct $produit, $delaiStock, $dateDuMois, $dateFinActivite, $tousLesExercices) {
        $this->produit = $produit;
        $this->delaiStock = $delaiStock;
        $this->dateDuMois = $dateDuMois;
        $this->dateFinActivite = $dateFinActivite;
        $this->tousLesExercices = $tousLesExercices;

        $this->init();
    }

    private function init() {
        $dateValeurDuStock = clone $this->dateDuMois;
        $dateValeurDuStock->add(new \DateInterval('P1M'));
        $valeurStock = 0;
        if ($this->delaiStock > 0) {
            $coutMensuelProduit = $this->getCoutMensuelParProduit();
            $valeurStock = $this->calculStockProduit($coutMensuelProduit, $this->delaiStock, $dateValeurDuStock, $valeurStock);
        }
        $this->valeurStock = $valeurStock;
    }

    private function calculStockProduit($coutMensuelProduit, $delaiStock, $date, $valeurStock) {
        $dateMoisSuivant = clone $date;
        $dateMoisSuivant->add(new \DateInterval('P1M'));

        $nbJourDuMois = Helper::nbDayInMonth($date);
        $nbJourRestant = $delaiStock - $nbJourDuMois;
        $coutMensuel = $this->getCoutMensuelByDate($coutMensuelProduit, $date);
        if ($nbJourRestant > 0) {
            $valeurStock += $coutMensuel;
            $date->add(new \DateInterval('P1M'));

            return $this->calculStockProduit($coutMensuelProduit, $nbJourRestant, $date, $valeurStock);
        } else {
            $valeurStock += ($delaiStock / $nbJourDuMois) * $coutMensuel;
        }

        return $valeurStock;
    }

    public function getProduit() {
        return $this->produit;
    }

    public function getValeur() {
        return $this->valeurStock;
    }

    public function setValeur($valeur) {
        $this->valeurStock = $valeur;

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
                if ($infoProduct->getProduit()->getId() == $this->produit->getProduit()->getId()) {
                    $coutMensuel = array_merge($coutMensuel, $infoProduct->getCoutMensuel());
                }
            }
        }

        return $coutMensuel;
    }

    /**
     * @param $coutMensuelProduit
     * @param \DateTime $date
     * @return mixed
     */
    private function getCoutMensuelByDate($coutMensuelProduit, \DateTime $date) {
        $valeur = 0;

        $keyFormat = $date->format('Y-m-d');

        if (array_key_exists($keyFormat, $coutMensuelProduit)) {
            $valeur = $coutMensuelProduit[$keyFormat];
        } else {
            $unAnPlusTard = clone $date;
            $unAnPlusTard->sub(new \DateInterval('P1Y'));
            $keyFormatUnAnPlusTard = $unAnPlusTard->format('Y-m-d');

            $deuxAnPlusTard = clone $date;
            $deuxAnPlusTard->sub(new \DateInterval('P2Y'));
            $keyFormatDeuxAnPlusTard = $deuxAnPlusTard->format('Y-m-d');

            if (array_key_exists($keyFormatUnAnPlusTard, $coutMensuelProduit) && array_key_exists($keyFormatDeuxAnPlusTard, $coutMensuelProduit) && $coutMensuelProduit[$keyFormatDeuxAnPlusTard] != 0) {
                $valeur = $coutMensuelProduit[$keyFormatUnAnPlusTard] * ($coutMensuelProduit[$keyFormatUnAnPlusTard] / $coutMensuelProduit[$keyFormatDeuxAnPlusTard]);
            }
        }

        return $valeur;
    }

}
