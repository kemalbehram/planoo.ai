<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Smic;
use BPBundle\Entity\Staff;

class ChargePersonnel {

    protected $staffs;
    protected $dateDuMois;
    protected $dateExercice;
    protected $smic;
    protected $sommeChargeSalarialeMensuelle;
    protected $sommeChargePatronaleMensuelle;
    protected $sommeChargePersonnelMensuelle;
    protected $sommeMasseSalarialeHorsTNS;
    protected $sommeMasseSalarialeTNS;
    protected $sommeCiceMensuel;
    protected $exercice;

    /**
     * ChargePersonnel constructor.
     * @param $staffs
     * @param $dateDuMois
     * @param $smic
     */
    public function __construct($staffs, $dateDuMois, $exercice, Smic $smic) {
        $this->staffs = $staffs;
        $this->dateDuMois = $dateDuMois;
        $this->dateExercice = $exercice->getDateDebut();
        $this->exercice = $exercice;
        $this->smic = $smic;

        $this->init();
    }

    public function getSommeMasseSalarialeHorsTNS() {
        return $this->sommeMasseSalarialeHorsTNS;
    }

    public function getSommeMasseSalarialeTNS() {
        return $this->sommeMasseSalarialeTNS;
    }

    public function getSommeCiceMensuel() {
        return $this->sommeCiceMensuel;
    }

    public function getSommeChargePersonnelMensuelle() {
        return $this->sommeChargePersonnelMensuelle * -1;
    }

    public function getSommeChargePatronaleMensuelle() {
        return $this->sommeChargePatronaleMensuelle * -1;
    }

    private function init() {
        $this->sommeSalaireMensuel = 0;
        $this->sommeCiceMensuel = 0;
        $this->sommeChargePersonnelMensuelle = 0;
        $this->sommeChargePatronaleMensuelle = 0;

        foreach ($this->staffs as $staff) {

            if (Helper::isInPeriod($staff->getCreatedAt(), $staff->getFinishedAt(), $this->dateDuMois)) {
                //If not TNS. TNS is calculated annualy
                if ($staff->getStatus()->getId() != 1) {
                    $this->calculateChargesDefaultStaff($staff);
                } else {
                    $this->calculateChargesTNSStaff($staff);
                }
            }
        }
    }

    private function getCiceMensuel(Staff $staff, $salaireMensuelBrut) {
        //TODO Filter sociales charges by country
        if ($staff->getStatus()->getChargesSociales()[0]->getCice() != 0) {
            $salaireHoraire = $staff->getIncome() / ($staff->getHours() / 100);
            if ($salaireHoraire < ($this->smic->getMontant() * $this->smic->getCoef())) {
                return $salaireMensuelBrut * ($this->smic->getCice() / 100);
            }
        }

        return 0;
    }

    private function calculateChargesDefaultStaff($staff) {
        $dataChargesSociales = $staff->getStatus()->getChargesSociales()[0];
        $salaireMensuel = $staff->getIncome();
        $chargeSalariale = $salaireMensuel * (($dataChargesSociales ? $dataChargesSociales->getChargeSalariale() : 0) / 100);
        $salaireMensuelBrut = $salaireMensuel + $chargeSalariale;
        $chargePatronale = $salaireMensuelBrut * (($dataChargesSociales ? $dataChargesSociales->getChargePatronale() : 0) / 100) - $this->getCiceMensuel($staff, $salaireMensuelBrut);
        $staff->setChargeSociale($chargeSalariale + $chargePatronale);

        $primePrecarite = 0;

        if ($staff->getFinishedAt() != null && Helper::nbMonthDiff($this->dateDuMois, $staff->getFinishedAt()) == 0) {
            $sommeSalairesBrutes = Helper::nbMonthDiff($staff->getCreatedAt(), $staff->getFinishedAt()) * $salaireMensuel * (1 + (($dataChargesSociales ? $dataChargesSociales->getChargeSalariale() : 0) / 100));
            $primePrecarite = 0.1 * $sommeSalairesBrutes;
        }


        $this->sommeChargePatronaleMensuelle += $chargePatronale;
        $this->sommeChargeSalarialeMensuelle += $chargeSalariale;
        $this->sommeChargePersonnelMensuelle += $chargeSalariale + $salaireMensuel + $primePrecarite;
        $masseSalarialePersonnel = $salaireMensuelBrut + $chargePatronale + $primePrecarite;
        $this->sommeMasseSalarialeHorsTNS += $masseSalarialePersonnel;

        // calcul par année de la masse salariale d'un employé
        $detailChargeSocialeStaff = $staff->getDetailMasseSalariale();
        $annee = $this->dateExercice->format('mY');
        if (!empty($detailChargeSocialeStaff[$annee])) {
            $detailChargeSocialeStaff[$annee] += $masseSalarialePersonnel - $chargePatronale;
        } else {
            $detailChargeSocialeStaff[$annee] = $masseSalarialePersonnel - $chargePatronale;
        }
        $staff->setDetailMasseSalariale($detailChargeSocialeStaff);

        $detailChargePatronaleStaff = $staff->getDetailMassePatronale();
        if (!empty($detailChargePatronaleStaff[$annee])) {
            $detailChargePatronaleStaff[$annee] += $chargePatronale;
        } else {
            $detailChargePatronaleStaff[$annee] = $chargePatronale;
        }
        $staff->setDetailMassePatronale($detailChargePatronaleStaff);
    }

    private function calculateChargesTNSStaff($staff) {
        $salaireMensuel = $staff->getIncome();
        $salaireMensuelBrut = $salaireMensuel;
        $staff->setChargeSociale(0);

        $this->sommeChargePersonnelMensuelle += $salaireMensuel;

        // calcul par année de la masse salariale d'un employé
        $detailChargeSocialeStaff = $staff->getDetailMasseSalariale();
        $annee = $this->dateExercice->format('mY');
        if (!empty($detailChargeSocialeStaff[$annee])) {
            $detailChargeSocialeStaff[$annee] += $salaireMensuel;
        } else {
            $detailChargeSocialeStaff[$annee] = $salaireMensuel;
        }
        $staff->setDetailMasseSalariale($detailChargeSocialeStaff);
        $this->sommeMasseSalarialeTNS += $salaireMensuel;
        $this->exercice->setMasseSalarialeTNS($this->exercice->getMasseSalarialeTNS() + $salaireMensuel);
    }

}
