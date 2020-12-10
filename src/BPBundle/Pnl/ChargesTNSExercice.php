<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Rate;

class ChargesTNSExercice {

    protected $chargesTNS;
    protected $sommeRcaiNet;
    protected $sommeChargesTNSExercice;
    protected $sommeChargesTNSExerciceHorsRCAI;
    protected $staffs;
    protected $isIr;
    protected $isPersonneMorale;
    protected $exercice;

    /**
     * ISExercice constructor.
     * @param $tauxIS
     * @param $sommeCaExercice
     */
    public function __construct($exercice, $staffs, $sommeRcaiNet, $bpInformation) {
        $this->chargesTNS = [
            'maladie1' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => 202620,
                    'rate' => 0.0635,
                    'includeSocialCharges' => false
                ]
            ], 'maladie2' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => 202620,
                    'rate' => 0.085,
                    'includeSocialCharges' => false
                ]
            ], 'allocFam' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => null,
                    'rate' => 0.031,
                    'includeSocialCharges' => false
                ]
            ], 'retraiteBase' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => 40524,
                    'rate' => 0.1775,
                    'includeSocialCharges' => false
                ], [
                    'assieteMin' => 40525,
                    'assietteMax' => null,
                    'rate' => 0.006,
                    'includeSocialCharges' => false
                ],
            ], 'retraiteComplementaire' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => 37960,
                    'rate' => 0.07,
                    'includeSocialCharges' => false
                ], [
                    'assieteMin' => 37961,
                    'assietteMax' => 162096,
                    'rate' => 0.08,
                    'includeSocialCharges' => false
                ],
            ], 'invaliditeDeces' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => 40524,
                    'rate' => 0.013,
                    'includeSocialCharges' => false
                ]
            ], 'csgCrds' => [
                [
                    'assieteMin' => 0,
                    'assietteMax' => null,
                    'rate' => 0.097,
                    'includeSocialCharges' => true
                ]
            ]
        ];
        $this->sommeRcaiNet = $sommeRcaiNet;
        $this->staffs = $staffs;
        $this->isIr = $bpInformation->getIr();
        $this->isPersonneMorale = $bpInformation->getLegalForm()->getPersonneMorale();
        $this->exercice = $exercice;

        $this->init();
    }

    public function getSommeChargesTNSExercice() {
        return $this->sommeChargesTNSExercice;
    }

    public function getSommeChargesTNSExerciceHorsRCAI() {
        return $this->sommeChargesTNSExerciceHorsRCAI;
    }

    private function init() {
        $assietteChargesTNSHorsRCAI = 0;

        foreach ($this->staffs as $staff) {

            //If TNS Only
            if ($staff->getStatus()->getId() == 1) {
                //calculate yearly salary
                $salaireMensuel = $staff->getIncome();
                $dateFin = null;
                $dateDebut = $this->exercice->getDateDebut() > $staff->getStaffCreatedAt() ? $this->exercice->getDateDebut() : $staff->getStaffCreatedAt();
                if ($staff->getFinishedAt()) {
                    $dateFin = $this->exercice->getDateFin() < $staff->getFinishedAt() ? $this->exercice->getDateFin() : $staff->getFinishedAt();
                } else {
                    $dateFin = $this->exercice->getDateFin();
                }
                $nbMois = Helper::nbMonthDiff($dateDebut, $dateFin) + 1;
                $assietteChargesTNSHorsRCAI += $salaireMensuel * $nbMois;
            }
        }

        $this->sommeChargesTNSExerciceHorsRCAI = $this->calculSommeChargesTNS($assietteChargesTNSHorsRCAI) * -1;

        $assietteChargesTNS = $assietteChargesTNSHorsRCAI;
        if ($this->isIr && (!$this->isPersonneMorale || ($this->isPersonneMorale && $this->sommeRcaiNet > 0))) {
            $assietteChargesTNS += $this->sommeRcaiNet + $this->sommeChargesTNSExerciceHorsRCAI;
        }

        $this->sommeChargesTNSExercice = $this->calculSommeChargesTNS($assietteChargesTNS) * -1;
    }

    private function calculSommeChargesTNS($assiette) {
        $sommeChargesTNS = 0;
        foreach ($this->chargesTNS as $chargeTNS) {
            foreach ($chargeTNS as $chargeTranche) {
                $tempAssiette = $assiette;
                if ($chargeTranche['assietteMax'] != null) {
                    $tempAssiette = $tempAssiette > $chargeTranche['assietteMax'] ? $chargeTranche['assietteMax'] : $tempAssiette;
                }
                $tempAssiette = $tempAssiette - $chargeTranche['assieteMin'] > 0 ? $tempAssiette - $chargeTranche['assieteMin'] : 0;
                $sommeChargesTNS += $tempAssiette * $chargeTranche['rate'];
            }
        }

        return $sommeChargesTNS;
    }

}
