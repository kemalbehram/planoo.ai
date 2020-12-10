<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\InfoCharge;

class CoutVariable {

    protected $infoCharges;
    protected $exercice;
    protected $sommeCaMensuel;
    protected $sommeCommissionMensuelle;
    protected $sommeChargeVariableSurChargeExterneMensuelle;

    public function __construct($exercice, $sommeCaMensuel, $sommeCommissionMensuelle) {
        $this->exercice = $exercice;
        $this->infoCharges = $exercice->getInfoCharge();
        $this->sommeCaMensuel = $sommeCaMensuel;
        $this->sommeCommissionMensuelle = $sommeCommissionMensuelle;

        $this->init();
    }

    public function getSommeChargeVariableSurChargeExterneMensuelle() {
        return $this->sommeChargeVariableSurChargeExterneMensuelle * -1;
    }

    public function getSommeAchatVariableMensuel() {
        return $this->getSommeChargeVariableSurChargeExterneMensuelle() + $this->sommeCommissionMensuelle;
    }

    private function init() {
        $dateDebut = clone $this->exercice->getDateDebut();
        $dateFin = clone $this->exercice->getDateFin();
        $nbMonth = 0;
        while ($dateDebut < $dateFin) {
            $nbMonth++;
            $dateDebut->add(new \DateInterval('P1M'));
        }

        $this->sommeChargeVariableSurChargeExterneMensuelle = 0;
        foreach ($this->infoCharges as $infoCharge) {
            $coutAnnuel = $this->getChargeVariableSurChargeExterneMensuelle($infoCharge);
            $this->sommeChargeVariableSurChargeExterneMensuelle += $coutAnnuel;

            $dateDebut = clone $this->exercice->getDateDebut();
            $dateFin = clone $this->exercice->getDateFin();
            while ($dateDebut < $dateFin) {
                $infoCharge->addCoutMensuel($dateDebut, $coutAnnuel / $nbMonth);
                $dateDebut->add(new \DateInterval('P1M'));
            }
        }
    }

    private function getChargeVariableSurChargeExterneMensuelle(InfoCharge $infoCharge) {
        return ($infoCharge->getCharge()->getTaux() / 100) * $this->sommeCaMensuel;
    }

}
