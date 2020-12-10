<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;
use Doctrine\Common\Collections\ArrayCollection;

class DispatchFournisseur {

    protected $infoBpMensuel;
    protected $infoBfr;
    protected $dateFinActivite;
    protected $tva;

    /**
     * Stock constructor.
     * @param $infoProduits
     */
    public function __construct($infoBpMensuel, $infoBfr, $dateFinActivite, $tva) {
        $this->infoBpMensuel = $infoBpMensuel;
        $this->infoBfr = $infoBfr;
        $this->dateFinActivite = $dateFinActivite;
        $this->tva = $tva;
    }

    public function getValeurBfrFournisseur() {
        $tabDetailFournisseurParDate = [];

        $tabSommeCoutsGlobaux = [];
        foreach ($this->infoBpMensuel as $bpMensuel) {
            $dateValeurFournisseur = clone $bpMensuel->getDate();
            $keyFormat = $dateValeurFournisseur->format('Y-m-d');
            $tabSommeCoutsGlobaux[$keyFormat] = (
                    $bpMensuel->getCoutDeRevientMensuel() +
                    $bpMensuel->getAchatVariableMensuel() +
                    $bpMensuel->getChargeFixeSurChargeMensuelle()
                    );
        }

        foreach ($this->infoBpMensuel as $bpMensuel) {
            $valeur = 0;
            $delai = $this->infoBfr->getProvider();
            $dateValeurFournisseur = clone $bpMensuel->getDate();
            $valeur = $this->calcul($tabSommeCoutsGlobaux, $delai, $dateValeurFournisseur, $valeur);

            $key = $dateValeurFournisseur->format('Y-m-d');
            $fournisseur = new Fournisseur();
            $fournisseur->setValeur($valeur);

            $tabDetailFournisseurParDate[$key] = $fournisseur;
        }

        return $tabDetailFournisseurParDate;
    }

    private function calcul($tabSommeCoutsGlobaux, $delai, $date, $valeur) {
        $currentDate = clone $date;
        $nbJourDuMois = Helper::nbDayInMonth($currentDate);
        $nbJourRestant = $delai - $nbJourDuMois;
        $sommeCoutGlobaux = $this->getSommeCoutsGlobauxByDate($tabSommeCoutsGlobaux, $currentDate);
        if ($nbJourRestant > 0) {
            $valeur += $sommeCoutGlobaux * (1 + ($this->tva / 100));
            $currentDate->sub(new \DateInterval('P1M'));
            return $this->calcul($tabSommeCoutsGlobaux, $nbJourRestant, $currentDate, $valeur);
        } else {
            $valeur += (($delai / $nbJourDuMois) * $sommeCoutGlobaux) * (1 + ($this->tva / 100));
        }

        return $valeur;
    }

    private function getSommeCoutsGlobauxByDate($tabSommeCoutsGlobaux, $date) {
        $valeur = 0;

        if (array_key_exists($date->format('Y-m-d'), $tabSommeCoutsGlobaux)) {
            $valeur = $tabSommeCoutsGlobaux[$date->format('Y-m-d')];
        }

        return $valeur;
    }

}
