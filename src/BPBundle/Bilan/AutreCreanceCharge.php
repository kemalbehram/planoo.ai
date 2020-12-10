<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\InfoCharge;
use BPBundle\Pnl\Helper;

class AutreCreanceCharge implements PosteChargeInterface {

    protected $infoCharge;
    protected $dateDuMois;
    protected $valeur;
    protected $tva;
    protected $tvaSurEncaissement;
    protected $delaiPaiementFournisseur;
    protected $tousLesExercices;
    protected $fournisseurChargesOrganiserParDate;

    /**
     * StockProduit constructor.
     * @param $infoProduit
     */
    public function __construct(InfoCharge $infoCharge, $delaiPaiementFournisseur, $dateDuMois, $tva, $tvaSurEncaissement, $tousLesExercices, $fournisseurChargesOrganiserParDate) {
        $this->infoCharge = $infoCharge;
        $this->dateDuMois = $dateDuMois;
        $this->tva = $infoCharge->getCharge()->getTva() !== null ? $infoCharge->getCharge()->getTva() : $tva;
        $this->tvaSurEncaissement = $tvaSurEncaissement;
        $this->delaiPaiementFournisseur = $infoCharge->getCharge()->getProviderDelay() !== null ? $infoCharge->getCharge()->getProviderDelay() : $delaiPaiementFournisseur;
        $this->tousLesExercices = $tousLesExercices;
        $this->fournisseurChargesOrganiserParDate = $fournisseurChargesOrganiserParDate;

        $this->init();
    }

    private function init() {
        $this->initTVACharges();
    }

    private function initTVACharges() {

        $moisPaiement = [];

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

        $dateMMoins1 = clone $this->dateDuMois;

        $dateMMoins1->sub(new \DateInterval('P1M'));
        //calcul assiette TVA payee en M (declaré en M à ajouter au creance/dette de M)
        $assietteTVAMoisEnCours = $this->getAssietteTVA($this->dateDuMois, $moisPaiement);
        $tvaSurChargeEnCours = $assietteTVAMoisEnCours * ($this->tva / 100);
        //calcul assiette TVA M-1 (déclaré en M-1 a Payer en M) (A payer)

        $assietteTVAMoisMoins1 = $this->getAssietteTVA($dateMMoins1, $moisPaiement);
        $tvaSurChargeMoisMoins1 = $assietteTVAMoisMoins1 * ($this->tva / 100);

        if ($this->tvaSurEncaissement) {
            $dateMMoins2 = clone $dateMMoins1;
            $dateMMoins2->sub(new \DateInterval('P1M'));

            $detteFournisseurMMoins1 = $this->getDetteFournisseurChargeByDate($dateMMoins1);
            $detteFournisseurMMoins2 = $this->getDetteFournisseurChargeByDate($dateMMoins2);

            $tvaDecaissementMMoins1 = ($assietteTVAMoisMoins1 - (($detteFournisseurMMoins2 - $detteFournisseurMMoins1) / (1 + $this->tva / 100))) * ($this->tva / 100);

            $this->valeur = ($tvaSurChargeEnCours - $tvaDecaissementMMoins1);
        } else {
            $this->valeur += ($tvaSurChargeEnCours - $tvaSurChargeMoisMoins1);
        }
    }

    public function getCharge() {
        return $this->infoCharge;
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

    public function getAssietteTVA($date, $moisPaiement) {


        $coutMensuel = 0;
        $tousLesCoutMensuelCharge = $this->getCoutMensuelParCharge();

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

    private function getDetteFournisseurChargeByDate($date) {
        $detteFournisseur = 0;

        $key = $date->format('Y-m-d');

        if (array_key_exists($key, $this->fournisseurChargesOrganiserParDate)) {
            $elementBfr = $this->fournisseurChargesOrganiserParDate[$key];
            foreach ($elementBfr->getDetailPoste() as $fournisseurProduit) {
                //instance of ClientProduit
                if ($fournisseurProduit->getCharge()->getCharge()->getId() == $this->infoCharge->getCharge()->getId()) {
                    $detteFournisseur = $fournisseurProduit->getValeur();
                }
            }
        }
        return $detteFournisseur;
    }

}
