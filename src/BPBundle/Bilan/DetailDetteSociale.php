<?php


namespace BPBundle\Bilan;


class DetailDetteSociale {

    protected $staff;

    protected $urssaf;

    protected $retraite;

    protected $sommeValeur;

    /**
     * DetailDetteSociale constructor.
     * @param $staff
     * @param $urssaf
     * @param $retraite
     */
    public function __construct($staff, $urssaf, $retraite) {
        $this->staff = $staff;
        $this->urssaf = $urssaf;
        $this->retraite = $retraite;
        $this->sommeValeur = ($urssaf + $retraite) * -1;
    }

    public function getSommeValeur() {
        return $this->sommeValeur;
    }
}