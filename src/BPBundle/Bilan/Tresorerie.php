<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\Funding;
use BPBundle\Pnl\Helper;

class Tresorerie {

    protected $dateDuMois;
    protected $sommeEmpruntBancaire = 0;
    protected $apportCompteCourantAssocie = 0;
    protected $remboursementCompteCourantAssocie = 0;
    protected $soldeCompteCourantAssocie = 0;
    private static $traitementCompteCourantAssocie = 0;
    protected $sommeSubventionRemboursable = 0;
    protected $fluxTresorerie;
    protected $tresorerieNette;

    /**
     * Tresorerie constructor.
     * @param $dateDuMois
     */
    public function __construct($dateDuMois) {
        $this->dateDuMois = $dateDuMois;
    }

    public function init($financements) {
        foreach ($financements as $financement) {
            $this->calculEmpruntBancaire($financement);
            $this->calculCompteCourantAssocie($financement);
            $this->calculSubventionRemboursable($financement);
        }
    }

    private function calculEmpruntBancaire(Funding $financement) {
        if ($financement->getChargeLabel()->getIsEmpruntBancaire()) {

            if ($this->inDifferePhase($financement)) {

                $amount = $financement->getAmount();

                $taux_interet_annuel = $financement->getRate();
                $taux_interet_mensuel = (( pow(1 + ($taux_interet_annuel / 100), 1 / 12) ) - 1);

                $interets = $amount * $taux_interet_mensuel;

                $amount += $interets;
                $financement->setAmount($amount);

                $annee = $this->dateDuMois->format('mY');
                $detail = $financement->getDetailEmpruntBancaire();
                $detail[$annee] = $financement->getAmountTresorerieTmp();
                $financement->setDetailEmpruntBancaire($detail);
            } elseif ($this->inAmortissementPhase($financement)) {

                if ($financement->getAmountTresorerieTmp() == null) {
                    $financement->setAmountTresorerieTmp($financement->getAmount());
                }
                $restant = $financement->getAmountTresorerieTmp();
                $taux_interet_annuel = $financement->getRate();
                $taux_interet_mensuel = (( pow(1 + ($taux_interet_annuel / 100), 1 / 12) ) - 1);

                $annuite = 0;
                if ($taux_interet_mensuel != 0) {
                    $annuite = ($financement->getAmount() / (( 1 - pow((1 + $taux_interet_mensuel), (-$financement->getWithin()))) / $taux_interet_mensuel));
                } else {
                    $annuite = ($financement->getAmount() / (-$financement->getWithin())) * -1;
                }
                $remboursement = $annuite - ($restant * $taux_interet_mensuel);

                $restant -= $remboursement;

                $this->sommeEmpruntBancaire += ($restant * -1);
                $financement->setAmountTresorerieTmp($restant);

                $annee = $this->dateDuMois->format('mY');
                $detail = $financement->getDetailEmpruntBancaire();
                $detail[$annee] = $financement->getAmountTresorerieTmp();
                $financement->setDetailEmpruntBancaire($detail);
            }
        }
    }

    private function calculCompteCourantAssocie(Funding $financement) {
        if ($financement->getChargeLabel()->getIsCompteCourantAssocie()) {
            if ($this->dateIsValid($financement)) {
                $this->apportCompteCourantAssocie += $financement->getAmount();
                self::$traitementCompteCourantAssocie += ($this->apportCompteCourantAssocie) * -1;
            }
        }

        if ($financement->getChargeLabel()->getIsRemboursementCompteAssocie()) {
            if ($this->dateIsValid($financement)) {
                $this->remboursementCompteCourantAssocie += $financement->getAmount();
                self::$traitementCompteCourantAssocie += (- $this->remboursementCompteCourantAssocie) * -1;
            }
        }


        $this->soldeCompteCourantAssocie = self::$traitementCompteCourantAssocie;
    }

    private function calculSubventionRemboursable(Funding $financement) {
        if ($financement->getChargeLabel()->getIsRemboursable()) {
            if ($this->dateIsValid($financement)) {

                if ($financement->getAmountSubventionTmp() != null) {
                    $valeur = $financement->getAmountSubventionTmp();
                } else {
                    $valeur = $financement->getAmount();
                }

                $valeur -= ($financement->getAmount() / $financement->getWithin());
                $this->sommeSubventionRemboursable += $valeur * -1;
                $financement->setAmountSubventionTmp($valeur);

                $annee = $this->dateDuMois->format('mY');
                if (!$detail = $financement->getDetailSubventionRemboursable()) {
                    $detail = [];
                }
                $detail[$annee] = $valeur;
                $financement->setDetailSubventionRemboursable($detail);
            }
        }
    }

    private function dateIsValid(Funding $financement) {
        $duree = $financement->getWithin();
        $dateDebutAmortissement = clone $financement->getCreatedAt();
        $dateDebutAmortissement->modify('first day of this month');
        $dateFinAmortissement = clone $dateDebutAmortissement;
        $duree = ($duree < 2 ? 1 : $duree - 1);
        $dateFinAmortissement->add(new \DateInterval('P' . ($duree - 1) . 'M'));
        return ($duree != 0 && Helper::isInPeriod($dateDebutAmortissement, $dateFinAmortissement, $this->dateDuMois));
    }

    private function inAmortissementPhase(Funding $financement) {
        $duree = $financement->getWithin();
        $dureeDiffere = $financement->getDiffere();

        $dateDebutAmortissement = clone $financement->getCreatedAt();
        $dateDebutAmortissement->modify('first day of this month');
        if ($dureeDiffere && $dureeDiffere != 0) {
            $dateDebutAmortissement->add(new \DateInterval('P' . ($dureeDiffere) . 'M'));
        }
        $dateFinAmortissement = clone $dateDebutAmortissement;
        $dateFinAmortissement->add(new \DateInterval('P' . ($duree - 1) . 'M'));
        return ($duree != 0 && Helper::isInPeriod($dateDebutAmortissement, $dateFinAmortissement, $this->dateDuMois));
    }

    private function inDifferePhase(Funding $financement) {
        $dureeDiffere = $financement->getDiffere();
        if ($dureeDiffere && $dureeDiffere != 0) {
            $dateDebutDiffere = clone $financement->getCreatedAt();
            $dateDebutDiffere->modify('first day of this month');
            $dateFinDiffere = clone $dateDebutDiffere;

            $dateFinDiffere->add(new \DateInterval('P' . ($dureeDiffere - 1) . 'M'));
            return ($dureeDiffere != 0 && Helper::isInPeriod($dateDebutDiffere, $dateFinDiffere, $this->dateDuMois));
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getDateEnCours() {
        return $this->dateDuMois;
    }

    /**
     * @param mixed $dateDuMois
     */
    public function setDateEnCours($dateDuMois) {
        $this->dateDuMois = $dateDuMois;

        return $this;
    }

    /**
     * @return int
     */
    public function getSommeEmpruntBancaire() {
        return $this->sommeEmpruntBancaire;
    }

    /**
     * @return int
     */
    public function getSoldeCompteCourantAssocie() {
        return $this->soldeCompteCourantAssocie;
    }

    /**
     * @return int
     */
    public function getSommeSubventionRemboursable() {
        return $this->sommeSubventionRemboursable;
    }

    /**
     * @return mixed
     */
    public function getFluxTresorerie() {
        return $this->fluxTresorerie;
    }

    /**
     * @param mixed $fluxTresorerie
     */
    public function setFluxTresorerie($fluxTresorerie) {
        $this->fluxTresorerie = $fluxTresorerie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTresorerieNette() {
        return $this->tresorerieNette;
    }

    /**
     * @param mixed $tresorerieNette
     */
    public function setTresorerieNette($tresorerieNette) {
        $this->tresorerieNette = $tresorerieNette;

        return $this;
    }

}
