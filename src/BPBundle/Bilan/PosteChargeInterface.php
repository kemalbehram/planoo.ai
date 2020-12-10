<?php

namespace BPBundle\Bilan;

interface PosteChargeInterface {

    public function getCharge();

    public function getValeur();

    public function setValeur($valeur);

    public function getDateDuMois();
}
