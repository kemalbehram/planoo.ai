<?php

namespace BPBundle\Pnl;

class ChargesTNSMensuelles {

    protected $exercices;
    protected $infoMensuels;

    /**
     * ISMensuel constructor.
     * @param $exercices
     * @param $infoMensuels
     */
    public function __construct($exercices, $infoMensuels) {
        $this->exercices = $exercices;
        $this->infoMensuels = $infoMensuels;

        $this->init();
    }

    private function init() {
        foreach ($this->infoMensuels as $key => $infoMensuel) {

            $exerciceEnCours = $this->exercices[$infoMensuel->getNumExercice() - 1];

            $chargeTNSMesuellesHorsRCAI = 0;
            $chargeTNSMesuellesRCAI = 0;

            if ($exerciceEnCours->getMasseSalarialeTNS() != 0) {
                $chargeTNSMesuellesHorsRCAI = $exerciceEnCours->getChargesTNSHorsRCAI() * ($infoMensuel->getMasseSalarialeTNSMensuelle() / $exerciceEnCours->getMasseSalarialeTNS());
            }

            if ($infoMensuel->getDate()->format('m') == '12') {
                $chargeTNSMesuellesRCAI += $exerciceEnCours->getChargesTNS() - $exerciceEnCours->getChargesTNSHorsRCAI();
            }

            $infoMensuel->setChargePatronaleMensuelle($infoMensuel->getChargePatronaleMensuelle() + $chargeTNSMesuellesHorsRCAI);
            $infoMensuel->setChargeSocialeExploitationMensuelle($chargeTNSMesuellesRCAI);
            $infoMensuel->setChargesTNSMensuelles($chargeTNSMesuellesHorsRCAI + $chargeTNSMesuellesRCAI);
            $infoMensuel->setEbe($infoMensuel->getEbe() + $chargeTNSMesuellesHorsRCAI + $chargeTNSMesuellesRCAI);
            $infoMensuel->setResultatExploitationMensuel($infoMensuel->getResultatExploitationMensuel() + $chargeTNSMesuellesHorsRCAI + $chargeTNSMesuellesRCAI);
            $infoMensuel->setRCAIMensuel($infoMensuel->getRCAIMensuel() + $chargeTNSMesuellesHorsRCAI + $chargeTNSMesuellesRCAI);
            $infoMensuel->setResultatNetMensuel($infoMensuel->getResultatNetMensuel() + $chargeTNSMesuellesHorsRCAI + $chargeTNSMesuellesRCAI);
        }
    }

}
