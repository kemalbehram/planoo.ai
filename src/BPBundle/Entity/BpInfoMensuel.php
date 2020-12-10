<?php

namespace BPBundle\Entity;

class BpInfoMensuel {

    protected $date;
    protected $ca;
    protected $achat;
    protected $margeBrute;
    protected $commission;
    protected $chargeVariableSurChargeExterne;
    protected $achatVariable;
    protected $margeSurCoutVariable;
    protected $chargeFixe;
    protected $chargePersonnel;
    protected $chargePatronale;
    protected $chargeSocialeExploitation;
    protected $cice;
    protected $impotEtTaxe;
    protected $ebe;
    protected $amortissement;
    protected $resultatExploitation;
    protected $resultatFinancier;
    protected $subventionNonRemboursable;
    protected $rcai;
    protected $impotSurSociete;
    protected $chargesTNS;
    protected $resultatNet;
    protected $numExercice;
    protected $masseSalarialeTNS = 0;

    /**
     * @return mixed
     */
    public function getNumExercice() {
        return $this->numExercice;
    }

    /**
     * @param mixed $numExercice
     */
    public function setNumExercice($numExercice) {
        $this->numExercice = $numExercice;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaMensuel() {
        return $this->ca;
    }

    /**
     * @param mixed $ca
     */
    public function setCaMensuel($ca) {
        $this->ca = $ca;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoutDeRevientMensuel() {
        return $this->achat;
    }

    /**
     * @param mixed $achat
     */
    public function setCoutDeRevientMensuel($achat) {
        $this->achat = $achat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMargeBruteMensuelle() {
        return $this->margeBrute;
    }

    /**
     * @param mixed $margeBrute
     */
    public function setMargeBruteMensuelle($margeBrute) {
        $this->margeBrute = $margeBrute;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommissionMensuelle() {
        return $this->commission;
    }

    /**
     * @param mixed $commission
     */
    public function setCommissionMensuelle($commission) {
        $this->commission = $commission;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChargeVariableSurChargeExterne() {
        return $this->chargeVariableSurChargeExterne;
    }

    /**
     * @param mixed $chargeVariableSurChargeExterne
     */
    public function setChargeVariableSurChargeExterneMensuelle($chargeVariableSurChargeExterne) {
        $this->chargeVariableSurChargeExterne = $chargeVariableSurChargeExterne;

        return $this;
    }

    public function getAchatVariableMensuel() {
        return $this->achatVariable;
    }

    public function setAchatVariableMensuel($achatVariable) {
        $this->achatVariable = $achatVariable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMargeSurCoutVariableMensuelle() {
        return $this->margeSurCoutVariable;
    }

    /**
     * @param mixed $margeSurCoutVariable
     */
    public function setMargeSurCoutVariableMensuelle($margeSurCoutVariable) {
        $this->margeSurCoutVariable = $margeSurCoutVariable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChargeFixeSurChargeMensuelle() {
        return $this->chargeFixe;
    }

    /**
     * @param mixed $chargeFixe
     */
    public function setChargeFixeSurChargeMensuelle($chargeFixe) {
        $this->chargeFixe = $chargeFixe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChargePersonnelMensuelle() {
        return $this->chargePersonnel;
    }

    /**
     * @param mixed $chargePersonnel
     */
    public function setChargePersonnelMensuelle($chargePersonnel) {
        $this->chargePersonnel = $chargePersonnel;

        return $this;
    }

    public function getChargePatronaleMensuelle() {
        return $this->chargePatronale;
    }

    /**
     * @param mixed $chargePatronale
     */
    public function setChargePatronaleMensuelle($chargePatronale) {
        $this->chargePatronale = $chargePatronale;

        return $this;
    }

    public function getChargeSocialeExploitationMensuelle() {
        return $this->chargeSocialeExploitation;
    }

    public function setChargeSocialeExploitationMensuelle($chargeSocialeExploitation) {
        $this->chargeSocialeExploitation = $chargeSocialeExploitation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImpotEtTaxeMensuel() {
        return $this->impotEtTaxe;
    }

    /**
     * @param mixed $impotEtTaxe
     */
    public function setImpotEtTaxeMensuel($impotEtTaxe) {
        $this->impotEtTaxe = $impotEtTaxe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEbe() {
        return $this->ebe;
    }

    /**
     * @param mixed $ebe
     */
    public function setEbe($ebe) {
        $this->ebe = $ebe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmortissementMensuel() {
        return $this->amortissement;
    }

    /**
     * @param mixed $amortissement
     */
    public function setAmortissementMensuel($amortissement) {
        $this->amortissement = $amortissement;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultatExploitationMensuel() {
        return $this->resultatExploitation;
    }

    /**
     * @param mixed $resultatExploitation
     */
    public function setResultatExploitationMensuel($resultatExploitation) {
        $this->resultatExploitation = $resultatExploitation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultatFinancierMensuel() {
        return $this->resultatFinancier;
    }

    /**
     * @param mixed $resultatFinancier
     */
    public function setResultatFinancierMensuel($resultatFinancier) {
        $this->resultatFinancier = $resultatFinancier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubventionNonRemboursable() {
        return $this->subventionNonRemboursable;
    }

    /**
     * @param mixed $subventionNonRemboursable
     */
    public function setSubventionNonRemboursable($subventionNonRemboursable) {
        $this->subventionNonRemboursable = $subventionNonRemboursable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCiceMensuel() {
        return $this->cice;
    }

    /**
     * @param mixed $cice
     */
    public function setCiceMensuel($cice) {
        $this->cice = $cice;

        return $this;
    }

    public function getRcaiMensuel() {
        return $this->rcai;
    }

    public function setRcaiMensuel($rcai) {
        $this->rcai = $rcai;

        return $this;
    }

    public function getImpotSurSociete() {
        return $this->impotSurSociete;
    }

    public function setImpotSurSociete($impotSurSociete) {
        $this->impotSurSociete = $impotSurSociete;

        return $this;
    }

    public function getResultatNetMensuel() {
        return $this->resultatNet;
    }

    public function setResultatNetMensuel($resultatNet) {
        $this->resultatNet = $resultatNet;

        return $this;
    }

    public function getChargesTNSMensuelles() {
        return $this->chargesTNS;
    }

    public function setChargesTNSMensuelles($chargesTNS) {
        $this->chargesTNS = $chargesTNS;

        return $this;
    }

    public function getMasseSalarialeTNSMensuelle() {
        return $this->masseSalarialeTNS;
    }

    public function setMasseSalarialeTNSMensuelle($masseSalarialeTNS) {
        $this->masseSalarialeTNS = $masseSalarialeTNS;

        return $this;
    }

}
