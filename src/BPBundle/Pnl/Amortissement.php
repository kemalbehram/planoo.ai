<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Investissement;

class Amortissement {

    protected $investissements;
    protected $codeCountry;
    protected $dateDuMois;
    protected $dateExercice;
    protected $dateExercicePrecedent;
    protected $sommeAmortissementMensuel;

    /**
     * Amortissement constructor.
     * @param $investissements
     */
    public function __construct($investissements, $dateDuMois, $previousExercice, $dateExercice, $codeCountry) {
        $this->investissements = $investissements;
        $this->dateDuMois = $dateDuMois;
        $this->dateExercice = $dateExercice;
        if ($previousExercice != null) {
            $this->dateExercicePrecedent = $previousExercice->getDateDebut();
        }
        $this->codeCountry = $codeCountry;

        $this->init();
    }

    public function getSommeAmortissementMensuel() {
        return $this->sommeAmortissementMensuel * -1;
    }

    private function init() {
        $this->sommeAmortissementMensuel = 0;
        foreach ($this->investissements as $investissement) {
            $duree = Helper::getDureeAmortissementByInvestissement($investissement, $this->codeCountry);
            if (self::isValidAmortissement($investissement, $this->dateDuMois, $duree)) {
                $montant = self::calculAmortissement($investissement->getAmount(), $duree);
                $this->sommeAmortissementMensuel += $montant;

                $annee = $this->dateExercice->format('mY');
                $detail = $investissement->getDetailImmoNet();
                if (!empty($detail[$annee])) {
                    $detail[$annee] = ($detail[$annee] - $montant >= 0 ? $detail[$annee] - $montant : 0);
                } else {
                    if ($this->dateExercicePrecedent != null && !empty($detail[$this->dateExercicePrecedent->format('mY')])) {
                        $anneePrecedente = $this->dateExercicePrecedent->format('mY');
                        $detail[$annee] = ($detail[$anneePrecedente] - $montant >= 0 ? $detail[$anneePrecedente] - $montant : 0);
                    } else {
                        $detail[$annee] = ($investissement->getAmount() - $montant >= 0 ? $investissement->getAmount() - $montant : 0);
                    }
                }
                $investissement->setDetailImmoNet($detail);
            }
        }
    }

    public static function isValidAmortissement(Investissement $investissement, $dateDuMois, $duree) {
// Test permettant d'éviter d'ajouter un DateInterval de P-1M (voir ci-dessous)
        if ($duree == 0) {
            return true;
        } else {
            $dateDebutAmortissement = clone $investissement->getDate();
            $dateDebutAmortissement->modify('first day of this month');
            $dateFinAmortissement = clone $dateDebutAmortissement;
            $dateFinAmortissement->add(new \DateInterval('P' . ($duree - 1) . 'M'));
            return Helper::isInPeriod($dateDebutAmortissement, $dateFinAmortissement, $dateDuMois);
        }
    }

    public static function calculAmortissement($montant, $duree) {
        $somme = 0;

        if ($duree > 0) {
            $somme = $montant / $duree;
        }

        return $somme;
    }

}
