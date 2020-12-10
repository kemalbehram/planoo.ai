<?php

namespace BPBundle\Pnl;

use BPBundle\Entity\InfoProduct;
use BPBundle\Entity\Saisonnalite;
use BPBundle\Entity\Exercice;
use Doctrine\Common\Collections\ArrayCollection;

class Activite {

    protected $infoProducts;
    protected $exercice;
    protected $saisonnaliteMensuel;
    protected $sommeSaisonnalite;
    protected $dateDuMois;
    protected $sommeCaMensuel;
    protected $sommeCoutDeRevientMensuel;
    protected $sommeCommissionMensuelle;

    public function __construct(Exercice $exercice, $dateDuMois) {
        $this->infoProducts = $exercice->getInfoProduct();
        $this->exercice = $exercice;
        $this->dateDuMois = $dateDuMois;
        $this->init();
    }

    public function getSommeCaMensuel() {
        return $this->sommeCaMensuel;
    }

    public function getSommeCoutDeRevientMensuel() {
        return $this->sommeCoutDeRevientMensuel * -1;
    }

    public function getSommeCommissionMensuelle() {
        return $this->sommeCommissionMensuelle * -1;
    }

    public function getSommeMargeBruteMensuelle() {
        return $this->getSommeCaMensuel() + $this->getSommeCoutDeRevientMensuel();
    }

    private function init() {
        $this->sommeCaMensuel = 0;
        $this->sommeCoutDeRevientMensuel = 0;
        $this->sommeCommissionMensuelle = 0;

        foreach ($this->infoProducts as $infoProduct) {
            $caMensuelProduct = $this->getCaMensuel($infoProduct);
            $infoProduct->addCaMensuel($this->dateDuMois, $caMensuelProduct);
            $this->sommeCaMensuel += $caMensuelProduct;

            $coutMensuelProduct = $this->getCoutDeRevientMensuel($infoProduct);
            $infoProduct->addCoutMensuel($this->dateDuMois, $coutMensuelProduct);
            $this->sommeCoutDeRevientMensuel += $coutMensuelProduct;

            $commissionMensuelproduct = $this->getCommissionMensuel($infoProduct);
            $infoProduct->addCommissionMensuel($this->dateDuMois, $commissionMensuelproduct);
            $this->sommeCommissionMensuelle += $commissionMensuelproduct;
        }
    }

    private function getCaMensuel(InfoProduct $infoProduct) {
        $caMensuel = $infoProduct->getCAExercice() * $this->getTantieme($infoProduct);
        return $caMensuel;
    }

    private function getCoutDeRevientMensuel(InfoProduct $infoProduct) {
        $coutMensuel = $infoProduct->getCoutExercice() * $this->getTantieme($infoProduct);
        return $coutMensuel;
    }

    private function getCommissionMensuel(InfoProduct $infoProduct) {
        $commissionMensuel = ($infoProduct->getCommission() / 100) * $this->getCaMensuel($infoProduct);
        return $commissionMensuel;
    }

    private function getTantieme($infoProduct) {
        $this->sommeSaisonnalite = $infoProduct->getProduit()->sommeProductSeasonSaisonCAExercice($this->exercice);
        $this->saisonnaliteMensuel = $infoProduct->getProduit()->getProductSeasonByDate($this->dateDuMois);
        return $this->saisonnaliteMensuel->getSaisonCA() / $this->sommeSaisonnalite;
    }

}
