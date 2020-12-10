<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class AutreDette extends PosteBfr {

    protected $autreDetteAVenir;

    public function __construct($sommeAutreDetteProduitMoisPrecedent) {
        parent::__construct();
        $this->autreDetteAVenir = false;
        $this->sommeValeur = $sommeAutreDetteProduitMoisPrecedent;
    }

    /**
     * @return boolean
     */
    public function isAutreDetteAVenir() {
        return $this->autreDetteAVenir;
    }

    /**
     * @param boolean $autreCreanceAVenir
     */
    public function setAutreDetteAVenir($autreDetteAVenir) {
        $this->autreDetteAVenir = $autreDetteAVenir;

        return $this;
    }

}
