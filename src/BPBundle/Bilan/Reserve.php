<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;

class Reserve {

    protected $tableauReserve = [];

    public function init($exercices, $financements) {
        $sommeReserve = 0;
        foreach ($exercices as $key => $exercice) {
            $resultatNetExercicePrecedent = 0;
            if ($key > 0) {
                $exercicePrecedent = $exercices[$key - 1];
                $resultatNetExercicePrecedent = $exercicePrecedent->getRcai() + $exercicePrecedent->getIs();
            }

            $dateDebut = clone $exercice->getDateDebut();
            $dateFin = clone $exercice->getDateFin();
            while ($dateDebut <= $dateFin) {
                $currentDate = clone $dateDebut;

                $dividendeMensuel = 0;
                foreach ($financements as $financement) {
                    if ($financement->getChargeLabel()->getIsDividende()) {
                        $dateDeCreation = clone $financement->getCreatedAt();
                        $dateDeCreation->modify('first day of this month');
                        if ($dateDeCreation == $currentDate) {
                            $dividendeMensuel += $financement->getAmount();
                        }
                    }
                }


                $sommeReserve += $resultatNetExercicePrecedent;
                // Permet d'ajouter uniquement le resultat net de l'exercice précédent qu'une fois
                $resultatNetExercicePrecedent = 0;
                $sommeReserve -= $dividendeMensuel;
                $this->tableauReserve[$currentDate->format('Y-m-d')] = $sommeReserve;
                $dateDebut->add(new \DateInterval('P1M'));
            }

            $dividende = 0;
            foreach ($financements as $financement) {
                if ($financement->getChargeLabel()->getIsDividende()) {
                    $dateDeCreation = clone $financement->getCreatedAt();
                    $dateDeCreation->modify('first day of this month');
                    if (Helper::isInPeriod($exercice->getDateDebut(), $exercice->getDateFin(), $dateDeCreation)) {
                        $dividende += $financement->getAmount();
                    }
                }
            }
            $exercice->setReserve($resultatNetExercicePrecedent - $dividende);
        }
    }

    /**
     * @return array
     */
    public function getTableauReserve() {
        return $this->tableauReserve;
    }

}
