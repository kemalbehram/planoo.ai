<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoProduct;
use BPBundle\Pnl\Helper;

class ClientProduit implements PosteProduitInterface {

    protected $infoProduit;
    protected $delaiClient;
    protected $dateDuMois;
    protected $dateFinActivite;
    protected $valeurClient;
    protected $tousLesExercices;

    /**
     * StockProduit constructor.
     * @param $infoProduit
     */
    public function __construct(InfoProduct $infoProduit, $delaiClient, $dateDuMois, $dateFinActivite, $tva, $tousLesExercices) {
        $this->infoProduit = $infoProduit;
        $this->delaiClient = $infoProduit->getProduit()->getCustomerDelay() !== null ? $infoProduit->getProduit()->getCustomerDelay() : $delaiClient;
        $this->dateDuMois = $dateDuMois;
        $this->dateFinActivite = $dateFinActivite;
        $this->tousLesExercices = $tousLesExercices;
        $this->tva = $infoProduit->getProduit()->getTvaVentes() !== null ? $infoProduit->getProduit()->getTvaVentes() : $tva;

        $this->init();
    }

    private function init() {
        $dateValeurClient = clone $this->dateDuMois;
        $valeurClient = 0;
        if ($this->delaiClient > 0) {
            $caMensuelProduit = $this->getCaMensuelParProduit();
            $valeurClient = $this->calculClientProduit($caMensuelProduit, $this->delaiClient, $dateValeurClient, $valeurClient);
        }
        $this->valeurClient = $valeurClient;
    }

    private function calculClientProduit($caMensuelProduit, $delaiClient, $date, $valeurClient) {
        $nbJourDuMois = Helper::nbDayInMonth($date);
        $nbJourRestant = $delaiClient - $nbJourDuMois;
        $caMensuel = $this->getCaMensuelByDate($caMensuelProduit, $date);
        if ($nbJourRestant > 0) {
            $valeurClient += $caMensuel * (1 + ($this->tva / 100));
            $date->sub(new \DateInterval('P1M'));
            return $this->calculClientProduit($caMensuelProduit, $nbJourRestant, $date, $valeurClient);
        } else {
            $valeurClient += (($delaiClient / $nbJourDuMois) * $caMensuel) * (1 + ($this->tva / 100));
        }

        return $valeurClient;
    }

    public function getProduit() {
        return $this->infoProduit;
    }

    public function getValeur() {
        return $this->valeurClient;
    }

    public function setValeur($valeur) {
        $this->valeurClient = $valeur;

        return $this;
    }

    public function getDateDuMois() {
        return $this->dateDuMois;
    }

    /**
     * @return array
     */
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

    private function getCaMensuelByDate($caMensuelProduit, \DateTime $date) {
        $valeur = 0;

        $keyFormat = $date->format('Y-m-d');
        if (array_key_exists($keyFormat, $caMensuelProduit)) {
            $valeur = $caMensuelProduit[$keyFormat];
        }

        return $valeur;
    }

}
