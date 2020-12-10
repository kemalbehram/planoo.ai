<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;

class DispatchDetteSociale {

    protected $staffs;
    protected $dateDuMois;
    protected $dateFinActivite;

    const TRIMESTRE = 3;

    private static $debutEcheanceTrimestrielle = ['04', '07', '10', '01'];

    /**
     * DetteSociale constructor.
     * @param $staffs
     * @param $dateDuMois
     * @param $dateFinActivite
     */
    public function __construct($staffs, $dateDuMois, $dateFinActivite) {
        $this->staffs = $staffs;
        $this->dateDuMois = $dateDuMois;
        $this->dateFinActivite = $dateFinActivite;
    }

    public function getValeurBfrElement($detteSocialeOrganiserParDate) {
        foreach ($this->staffs as $staff) {
            $chargeSociale = $staff->getChargeSociale();
            $sommeUrssaf = $chargeSociale * (2 / 3);

            $sommeRetraite = $chargeSociale * (1 / 3);
            $dateDebutEmbauche = clone $staff->getCreatedAt();
            $dateFinEmbauche = clone ($staff->getFinishedAt() ? $staff->getFinishedAt() : $this->dateFinActivite);
            $dateDebutEmbauche->modify('first day of this month');
            $dateFinEmbauche->modify('first day of this month');

            $key = $this->dateDuMois->format('Y-m-d');
            $idStaff = $staff->getId();

            if (Helper::isInPeriod($staff->getCreatedAt(), $staff->getFinishedAt(), $this->dateDuMois)) {
                $detteSocialeOrganiserParDate[$key][$idStaff]['staff'] = $staff;

                // URSSAF
                $sommeUrssafTmp = $sommeUrssaf;
                $detteSocialeOrganiserParDate[$key][$idStaff]['urssaf'] = $sommeUrssafTmp;

                // RETRAITE
                $dateTrimestrielle = $this->getEcheanceTrimestrielle($this->dateDuMois);
                if (in_array($this->dateDuMois->format('Y-m-d'), $dateTrimestrielle)) {
                    $sommeRetraiteFinale = $sommeRetraite;
                } else {
                    $dateDuMoisPrecedent = clone $this->dateDuMois;
                    $dateDuMoisPrecedent->sub(new \DateInterval('P1M'));
                    $keyPrecedente = $dateDuMoisPrecedent->format('Y-m-d');
                    $chargeSocialePrecedente = 0;
                    if (array_key_exists($keyPrecedente, $detteSocialeOrganiserParDate)) {
                        if (array_key_exists($idStaff, $detteSocialeOrganiserParDate[$keyPrecedente])) {
                            $chargeSocialePrecedente = $detteSocialeOrganiserParDate[$keyPrecedente][$idStaff]['retraite'];
                        }
                    }
                    $sommeRetraiteFinale = $sommeRetraite + $chargeSocialePrecedente;
                }
                $detteSocialeOrganiserParDate[$key][$idStaff]['retraite'] = $sommeRetraiteFinale;
            } elseif ($this->dateDuMois > $dateFinEmbauche) {
                $derniereSommeRetraite = $detteSocialeOrganiserParDate[$dateFinEmbauche->format('Y-m-d')];
                $restantRetraite = $derniereSommeRetraite[$idStaff]['retraite'];
                $dateFinaleTrimestrielle = $this->getDateFinaleTrimestrielle($dateFinEmbauche);
                if ($this->dateDuMois < $dateFinaleTrimestrielle) {
                    $detteSocialeOrganiserParDate[$key][$idStaff]['urssaf'] = 0;
                    $detteSocialeOrganiserParDate[$key][$idStaff]['retraite'] = $restantRetraite;
                    $detteSocialeOrganiserParDate[$key][$idStaff]['staff'] = $staff;
                }
            }
        }

        return $detteSocialeOrganiserParDate;
    }

    private function getEcheanceTrimestrielle($dateDuMois) {
        $dateTrimestrielle = [];
        foreach (self::$debutEcheanceTrimestrielle as $mois) {
            $dateTrimestrielle[] = $dateDuMois->format('Y') . '-' . $mois . '-01';
        }

        return $dateTrimestrielle;
    }

    private function getDateFinaleTrimestrielle($dateFinEmbauche) {
        $currentDate = $dateFinEmbauche;
        foreach (self::$debutEcheanceTrimestrielle as $mois) {
            $currentDate = new \DateTime($dateFinEmbauche->format('Y') . '-' . $mois . '-01');
            if ($currentDate >= $dateFinEmbauche) {
                break;
            }
        }

        return $currentDate;
    }

}
