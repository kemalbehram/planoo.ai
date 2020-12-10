<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\Investissement;

class Helper {

    const POSTE_STOCK = 'stock';
    const POSTE_CLIENT = 'client';
    const POSTE_AUTRE_CREANCE = 'autre-creance';
    const POSTE_AUTRE_DETTE = 'autre-dette';
    const POSTE_FOURNISSEUR = 'fournisseur';

    public static function isInPeriod(\DateTime $dateDebutElement, $dateFinElement, \DateTime $dateEnCours) {
        $dateDebut = clone $dateDebutElement;
        $dateDebut->modify('first day of this month');
        $dateFin = $dateFinElement ? $dateFinElement : $dateEnCours;

        return $dateEnCours >= $dateDebut && $dateEnCours <= $dateFin;
    }

    public static function getDureeAmortissementByInvestissement(Investissement $investissement, $codeCountry) {
        $duree = 0;
        foreach ($investissement->getChargeLabel()->getAmortissements() as $amortissement) {
            if ($amortissement->getCountry()->getId() == $codeCountry) {
                $duree = $amortissement->getDuree();
                break;
            }
        }

        return $duree;
    }

    public static function nbDayInMonth($date) {
        return cal_days_in_month(CAL_GREGORIAN, $date->format('m'), $date->format('Y'));
    }

    public static function nbMonthDiff($dateStart, $dateEnd) {
        $dateS = clone $dateStart;
        $dateS->modify('first day of this month');
        $dateE = clone $dateEnd;
        $dateE->modify('first day of this month');
        $nb = 0;
        if ($dateS < $dateE) {
            while ($dateS < $dateE) {
                $dateS->modify('last day of this month');
                $nb++;
                $dateS->add(new \DateInterval('P1D'));
            }
        }

        return $nb;
    }

}
