<?php

namespace BPBundle\Bilan;

interface PosteProduitInterface {
    public function getProduit();
    public function getValeur();
    public function setValeur($valeur);
    public function getDateDuMois();
}