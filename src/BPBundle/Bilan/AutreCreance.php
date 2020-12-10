<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class AutreCreance extends PosteBfr {

    protected $autreCreanceAVenir;

    public function __construct($sommeAutreCreanceProduitMoisPrecedent) {
        parent::__construct();
        $this->autreCreanceAVenir = false;
        $this->sommeValeur = $sommeAutreCreanceProduitMoisPrecedent;
    }

    /**
     * @return boolean
     */
    public function isAutreCreanceAVenir() {
        return $this->autreCreanceAVenir;
    }

    /**
     * @param boolean $autreCreanceAVenir
     */
    public function setAutreCreanceAVenir($autreCreanceAVenir) {
        $this->autreCreanceAVenir = $autreCreanceAVenir;

        return $this;
    }

}
