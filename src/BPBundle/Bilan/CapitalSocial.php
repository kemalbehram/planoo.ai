<?php

namespace BPBundle\Bilan;

use BPBundle\Entity\Funding;
use BPBundle\Pnl\Helper;

class CapitalSocial {

    protected $dateDuMois;

    protected $sommeCapitalSocial;

    private static $capitalSocial = 0;

    protected $sommeReserve = 0;

    protected $sommeResultatExercice = 0;

    /**
     * CapitalSocial constructor.
     * @param $dateDuMois
     */
    public function __construct($dateDuMois, $dateDebutActivite, $capital)
    {
        $this->dateDuMois = $dateDuMois;
        if ($dateDebutActivite == $dateDuMois) {
            self::$capitalSocial = $capital;
        }
    }

    public function init($financements) {
        foreach($financements as $financement) {
            if ($financement->getChargeLabel()->getIsAugmentationCapital()) {
                if ($this->dateIsValid($financement)) {
                    self::$capitalSocial += $financement->getAmount();
                }
            }
        }
        $this->sommeCapitalSocial = self::$capitalSocial;
    }

    private function dateIsValid(Funding $financement) {
        $duree = $financement->getWithin();
        $dateDebutAmortissement = clone $financement->getCreatedAt();
        $dateDebutAmortissement->modify('first day of this month');
        $dateFinAmortissement = clone $dateDebutAmortissement;
        $duree = ($duree <= 1 ? 1 : $duree - 1);
        $dateFinAmortissement->add(new \DateInterval('P' . ($duree - 1) . 'M'));
        return  ($duree != 0 && Helper::isInPeriod($dateDebutAmortissement, $dateFinAmortissement, $this->dateDuMois));
    }

    /**
     * @return int
     */
    public function getSommeCapitalSocial()
    {
        return $this->sommeCapitalSocial;
    }

    /**
     * @return mixed
     */
    public function getSommeReserve()
    {
        return $this->sommeReserve;
    }

    /**
     * @param mixed $sommeReserve
     */
    public function setSommeReserve($sommeReserve)
    {
        $this->sommeReserve = $sommeReserve;

        return $this;
    }

    /**
     * @return int
     */
    public function getSommeResultatExercice()
    {
        return $this->sommeResultatExercice;
    }

    /**
     * @param int $sommeResultatExercice
     */
    public function setSommeResultatExercice($sommeResultatExercice)
    {
        $this->sommeResultatExercice = $sommeResultatExercice;

        return $this;
    }

}