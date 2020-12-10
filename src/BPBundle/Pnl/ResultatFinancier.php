<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Funding;

class ResultatFinancier {

    protected $fundings;
    protected $dateDuMois;
    protected $sommeResultatFinancierMensuel;
    protected $subventionNonRemboursable;

    /**
     * ResultatFinancier constructor.
     * @param $fundings
     */
    public function __construct($fundings, $dateDuMois) {
        $this->fundings = $fundings;
        $this->dateDuMois = $dateDuMois;
        $this->subventionNonRemboursable = 0;

        $this->init();
    }

    public function getSommeResultatFinancierMensuel() {
        return $this->sommeResultatFinancierMensuel * -1;
    }

    public function getSubventionNonRemboursable() {
        return $this->subventionNonRemboursable;
    }

    private function init() {
        $this->sommeResultatFinancierMensuel = 0;
        foreach ($this->fundings as $funding) {
            if ($funding->getChargeLabel()->getIsInteret()) {

                if ($this->dateIsValid($funding)) {

                    if ($funding->getAmountTmp() == null) {
                        $funding->setAmountTmp($funding->getAmount());
                    }
                    $restant = $funding->getAmountTmp();
                    $taux_interet_annuel = $funding->getRate();
                    $taux_interet_mensuel = (( pow(1 + ($taux_interet_annuel / 100), 1 / 12) ) - 1);

                    $interet_val = $restant * $taux_interet_mensuel;
                    $this->sommeResultatFinancierMensuel += $interet_val;

                    if ($taux_interet_mensuel != 0) {
                        $mensualite = ($funding->getAmount() / (( 1 - pow((1 + $taux_interet_mensuel), (-$funding->getWithin()))) / $taux_interet_mensuel));
                    } else {
                        $mensualite = $funding->getAmount();
                    }
                    $remboursement = $mensualite - ($restant * $taux_interet_mensuel);
                    $restant -= $remboursement;
                    $funding->setAmountTmp($restant);
                }
            }

            if ($funding->getChargeLabel()->getIsNonRemboursable() && $this->monthMatched($funding)) {
                $this->subventionNonRemboursable += $funding->getAmount();
            }
        }
    }

    private function dateIsValid(Funding $funding) {
        $duree = $funding->getWithin();
        // Test permettant d'éviter d'ajouter un DateInterval de P-1M (voir ci-dessous)
        if ($duree == 0) {
            $duree = 1;
        }
        $dateDebutAmortissement = clone $funding->getCreatedAt();
        $dateDebutAmortissement->modify('first day of this month');
        $dateFinAmortissement = clone $dateDebutAmortissement;
        $dateFinAmortissement->add(new \DateInterval('P' . ($duree - 1) . 'M'));
        return ($duree != 0 && Helper::isInPeriod($dateDebutAmortissement, $dateFinAmortissement, $this->dateDuMois));
    }

    private function monthMatched(Funding $funding) {
        $dateDebutAmortissement = clone $funding->getCreatedAt();
        $dateDebutAmortissement->modify('first day of this month');
        return $dateDebutAmortissement == $this->dateDuMois;
    }

}
