<?php

namespace BPBundle\Bilan;

class InfoBilanMensuel {

    protected $date;
    protected $actifsImmobilises;
    protected $bfr;
    protected $is;
    protected $ca;
    protected $chargesTNS;
    protected $detteFournisseurImmo;
    protected $tresorerie;
    protected $capitalSocial;
    protected $outOfExercice = false;
    protected $actifNet;
    protected $capitauxPropres;
    protected $numExercice;
    protected $valeurSouscriptionEmprunt;
    protected $achat;
    protected $commission;
    protected $achatVariable;
    protected $chargeVariableSurChargeExterne;
    protected $chargePersonnel;
    protected $cice;
    protected $chargePatronale;
    protected $impotsEtTaxes;

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

    public function getCa() {
        return $this->ca;
    }

    public function setCa($ca) {
        $this->ca = $ca;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActifsImmobilises() {
        return $this->actifsImmobilises;
    }

    /**
     * @param mixed $actifsImmobilises
     */
    public function setActifsImmobilises($actifsImmobilises) {
        $this->actifsImmobilises = $actifsImmobilises;

        return $this;
    }

    public function getAchat() {
        return $this->achat;
    }

    public function getCommission() {
        return $this->commission;
    }

    public function getAchatVariable() {
        return $this->achatVariable;
    }

    public function getChargeVariableSurChargeExterne() {
        return $this->chargeVariableSurChargeExterne;
    }

    public function setAchat($achat) {
        $this->achat = $achat;

        return $this;
    }

    public function setCommission($commission) {
        $this->commission = $commission;

        return $this;
    }

    public function setAchatVariable($achatVariable) {
        $this->achatVariable = $achatVariable;

        return $this;
    }

    public function setChargeVariableSurChargeExterne($chargeVariableSurChargeExterne) {
        $this->chargeVariableSurChargeExterne = $chargeVariableSurChargeExterne;

        return $this;
    }

    public function getImpotsEtTaxes() {
        return $this->impotsEtTaxes;
    }

    public function setImpotsEtTaxes($impotsEtTaxes) {
        $this->impotsEtTaxes = $impotsEtTaxes;

        return $this;
    }

    public function getChargePersonnel() {
        return $this->chargePersonnel;
    }

    public function getCice() {
        return $this->cice;
    }

    public function getChargePatronale() {
        return $this->chargePatronale;
    }

    public function setChargePersonnel($chargePersonnel) {
        $this->chargePersonnel = $chargePersonnel;

        return $this;
    }

    public function setCice($cice) {
        $this->cice = $cice;

        return $this;
    }

    public function setChargePatronale($chargePatronale) {
        $this->chargePatronale = $chargePatronale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBfr() {
        return $this->bfr;
    }

    /**
     * @param mixed $bfr
     */
    public function setBfr($bfr) {
        $this->bfr = $bfr;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIs() {
        return $this->is;
    }

    /**
     * @param mixed $is
     */
    public function setIs($is) {
        $this->is = $is;

        return $this;
    }

    public function getChargesTNS() {
        return $this->chargesTNS;
    }

    public function setChargesTNS($chargesTNS) {
        $this->chargesTNS = $chargesTNS;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetteFournisseurImmo() {
        return $this->detteFournisseurImmo;
    }

    /**
     * @param mixed $detteFournisseurImmo
     */
    public function setDetteFournisseurImmo($detteFournisseurImmo) {
        $this->detteFournisseurImmo = $detteFournisseurImmo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTresorerie() {
        return $this->tresorerie;
    }

    /**
     * @param mixed $tresorerie
     */
    public function setTresorerie($tresorerie) {
        $this->tresorerie = $tresorerie;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapitalSocial() {
        return $this->capitalSocial;
    }

    /**
     * @param mixed $capitalSocial
     */
    public function setCapitalSocial($capitalSocial) {
        $this->capitalSocial = $capitalSocial;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getOutOfExercice() {
        return $this->outOfExercice;
    }

    /**
     * @param boolean $outOfExercice
     */
    public function setOutOfExercice($outOfExercice) {
        $this->outOfExercice = $outOfExercice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActifNet() {
        return $this->actifNet;
    }

    /**
     * @param mixed $actifNet
     */
    public function setActifNet($actifNet) {
        $this->actifNet = $actifNet;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapitauxPropres() {
        return $this->capitauxPropres;
    }

    /**
     * @param mixed $capitauxPropres
     */
    public function setCapitauxPropres($capitauxPropres) {
        $this->capitauxPropres = $capitauxPropres;

        return $this;
    }

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

        return $this;
    }

    public function getValeurSouscriptionEmprunt() {
        return $this->valeurSouscriptionEmprunt;
    }

    public function setValeurSouscriptionEmprunt($valeurSouscriptionEmprunt) {
        $this->valeurSouscriptionEmprunt = $valeurSouscriptionEmprunt;

        return $this;
    }

}
