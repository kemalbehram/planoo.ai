<?php

namespace BPBundle\Pnl;

class ISMensuel {

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

            $isMensuel = 0;
            if ($exerciceEnCours->getRcai() != 0) {
                // IS mensuel
                $isMensuel = $exerciceEnCours->getIs() * ($infoMensuel->getRcaiMensuel() / $exerciceEnCours->getRcai());
            }
            $infoMensuel->setImpotSurSociete($isMensuel);

            // Résultat net
            $resultatNet = $infoMensuel->getRcaiMensuel() + $isMensuel;
            $infoMensuel->setResultatNetMensuel($resultatNet);
        }
    }

}
