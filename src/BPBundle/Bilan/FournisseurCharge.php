<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoCharge;
use BPBundle\Pnl\Helper;

class FournisseurCharge implements PosteChargeInterface {

    protected $infoCharge;
    protected $delaiFournisseur;
    protected $dateDuMois;
    protected $dateFinActivite;
    protected $valeurFournisseur;
    protected $tousLesExercices;
    protected $tva;
    protected $stockOrganiserParDate;

    /**
     * StockProduit constructor.
     * @param $infoCharge
     */
    public function __construct(InfoCharge $infoCharge, $delaiPaiementFournisseur, $sommeFournisseurChargeMoisPrecedent, $dateDuMois, $tva, $tousLesExercices) {
        $this->infoCharge = $infoCharge;
        $this->delaiPaiementFournisseur = $infoCharge->getCharge()->getProviderDelay() !== null ? $infoCharge->getCharge()->getProviderDelay() : $delaiPaiementFournisseur;
        $this->dateDuMois = $dateDuMois;
        $this->tousLesExercices = $tousLesExercices;
        $this->tva = $infoCharge->getCharge()->getTva() !== null ? $infoCharge->getCharge()->getTva() : $tva;
        if ($this->infoCharge->getCharge()->getPeriodicite() == 1 && $this->infoCharge->getCharge()->getTermeEchu()) {
            $this->valeurFournisseur = 0;
        } else {
            $this->valeurFournisseur = $sommeFournisseurChargeMoisPrecedent;
        }

        $this->init();
    }

    private function init() {
        $this->calculFournisseurCharge($this->delaiPaiementFournisseur, clone $this->dateDuMois);
    }

    private function calculFournisseurCharge($delaiFournisseur, $dateDuMois) {
        $moisPaiement = [];

        $nbJourDuMois = Helper::nbDayInMonth($dateDuMois);
        $nbJourRestant = $delaiFournisseur - $nbJourDuMois;

        $dateDebutBP = $this->infoCharge->getExercice()->getBusinessPlan()->getInformation()->getCreatedAt();
        $date = clone $this->infoCharge->getExercice()->getDateDebut();
        $dateFin = clone $this->infoCharge->getExercice()->getDateFin();

        if ($this->infoCharge->getCharge()->getTermeEchu()) {
            $dateFineAjustee = clone $dateFin;
            $dateFineAjustee->modify('last day of this month');
            $dateFineAjustee->add(new \DateInterval('P' . $this->delaiPaiementFournisseur . 'D'));
            while ($date < $dateFineAjustee) {
                if (Helper::nbMonthDiff($dateDebutBP, $date) != 0 && (Helper::nbMonthDiff($dateDebutBP, $date) + 1) % $this->infoCharge->getCharge()->getPeriodicite() == 0) {
                    $dateAjustee = clone $date;
                    $dateAjustee->modify('last day of this month');
                    $dateAjustee->add(new \DateInterval('P' . $this->delaiPaiementFournisseur . 'D'));
                    array_push($moisPaiement, $dateAjustee->format('m'));
                }
                $date->add(new \DateInterval('P1M'));
            }
        } else { //a echoir
            while ($date < $dateFin) {
                if (Helper::nbMonthDiff($dateDebutBP, $date) % $this->infoCharge->getCharge()->getPeriodicite() == 0) {
                    array_push($moisPaiement, $date->format('m'));
                }
                $date->add(new \DateInterval('P1M'));
            }
        }

        //TODO à corriger en prennant exemple sur AutreCreanceCharge
        if ($this->infoCharge->getCharge()->getPeriodicite() == 1 && $this->infoCharge->getCharge()->getTermeEchu()) {
            $keyFormat = $dateDuMois->format('Y-m-01');
            $assiette = 0;
            if (array_key_exists($keyFormat, $this->getCoutMensuelParCharge())) {
                $assiette += $this->getCoutMensuelParCharge()[$keyFormat];
            }
            if ($nbJourRestant > 0) {
                $this->valeurFournisseur += $assiette;
                $dateDuMois->sub(new \DateInterval('P1M'));
                return $this->calculFournisseurCharge($nbJourRestant, $dateDuMois);
            } else {
                $this->valeurFournisseur += (($delaiFournisseur / $nbJourDuMois) * ($assiette) * (1 + ($this->tva / 100)));
            }
        } else {

            $assiette = $this->getAssiette($this->dateDuMois, $moisPaiement);
            $this->valeurFournisseur += $assiette;
        }

        $this->valeurFournisseur *= -1;
    }

    public function getCharge() {
        return $this->infoCharge;
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

    public function getAssiette($date, $moisPaiement) {


        $coutMensuel = 0;
        $tousLesCoutMensuelCharge = $this->getCoutMensuelParCharge();
        //$dateReferenceAssiette=$date

        if (in_array($date->format('m'), $moisPaiement)) {

            $dateMoinsPrecedentOuSuivant = clone $date;

            if ($this->infoCharge->getCharge()->getTermeEchu()) {
                while (Helper::nbMonthDiff($dateMoinsPrecedentOuSuivant, $date) < $this->infoCharge->getCharge()->getPeriodicite()) {
                    $keyFormat = $dateMoinsPrecedentOuSuivant->format('Y-m-01');
                    if (array_key_exists($keyFormat, $tousLesCoutMensuelCharge)) {
                        $coutMensuel += $tousLesCoutMensuelCharge[$keyFormat];
                    }

                    $dateMoinsPrecedentOuSuivant->sub(new \DateInterval('P1M'));
                }
            } else {
                while (Helper::nbMonthDiff($date, $dateMoinsPrecedentOuSuivant) < $this->infoCharge->getCharge()->getPeriodicite()) {
                    $keyFormat = $dateMoinsPrecedentOuSuivant->format('Y-m-01');
                    if (array_key_exists($keyFormat, $tousLesCoutMensuelCharge)) {
                        $coutMensuel += $tousLesCoutMensuelCharge[$keyFormat];
                    }

                    $dateMoinsPrecedentOuSuivant->add(new \DateInterval('P1M'));
                }
            }
        }

        $keyFormat = $date->format('Y-m-01');
        if (array_key_exists($keyFormat, $tousLesCoutMensuelCharge)) {
            $coutMensuel -= $tousLesCoutMensuelCharge[$keyFormat];
        }

        $assiette = $coutMensuel;

        return($assiette);
    }

    private function getCoutMensuelParCharge() {
        $coutMensuel = [];
        foreach ($this->tousLesExercices as $exercice) {
            foreach ($exercice->getInfoCharge() as $infoCharge) {
                if ($infoCharge->getCharge()->getId() == $this->infoCharge->getCharge()->getId()) {
                    $coutMensuel = array_merge($coutMensuel, $infoCharge->getCoutMensuel());
                }
            }
        }

        return $coutMensuel;
    }

}
