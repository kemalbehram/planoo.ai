<?php

namespace BPBundle\Bilan;

class FluxTresorerie {

    protected $numExercice;
    protected $ca = 0;
    protected $achatsHorsStock = 0;
    protected $achatStock = 0;
    protected $personnel = 0;
    protected $impotsEtTaxes = 0;
    protected $variationBfr = 0;
    protected $fluxExploitation = 0;
    protected $resultatFinancier = 0;
    protected $resultatExceptionnel = 0;
    protected $subventionNonRemboursable = 0;
    protected $decaissement = 0;
    protected $fluxActivite = 0;
    protected $investissement = 0;
    protected $variationDetteFournisseurImmo = 0;
    protected $freeCashFlow = 0;
    protected $augmentationCapital = 0;
    protected $variationCompteCourant = 0;
    protected $variationEmprunt = 0;
    protected $subventionRemboursable = 0;
    protected $dividende = 0;
    protected $fluxFinancement = 0;
    protected $fluxDeTresorerie = 0;
    protected $tresorerieCloture = 0;
    protected $date;

    public function getCa() {
        return $this->ca;
    }

    public function getAchatsHorsStock() {
        return $this->achatsHorsStock;
    }

    public function getAchatStock() {
        return $this->achatStock;
    }

    public function getPersonnel() {
        return $this->personnel;
    }

    public function getImpotsEtTaxes() {
        return $this->impotsEtTaxes;
    }

    public function setCa($ca) {
        $this->ca = $ca;
    }

    public function setAchatsHorsStock($achatsHorsStock) {
        $this->achatsHorsStock = $achatsHorsStock;
    }

    public function setAchatStock($achatStock) {
        $this->achatStock = $achatStock;
    }

    public function setPersonnel($personnel) {
        $this->personnel = $personnel;
    }

    public function setImpotsEtTaxes($impotsEtTaxes) {
        $this->impotsEtTaxes = $impotsEtTaxes;
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

    /**
     * @return mixed
     */
    public function getEbe() {
        return $this->ebe;
    }

    /**
     * @param mixed $ebe
     */
    public function addEbe($ebe) {
        $this->ebe += $ebe;

        return $this;
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
    public function getVariationBfr() {
        return $this->variationBfr;
    }

    /**
     * @param mixed $variationBfr
     */
    public function addVariationBfr($variationBfr) {
        $this->variationBfr += $variationBfr;

        return $this;
    }

    /**
     * @param mixed $variationBfr
     */
    public function setVariationBfr($variationBfr) {
        $this->variationBfr = $variationBfr;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFluxExploitation() {
        return $this->fluxExploitation;
    }

    /**
     * @param mixed $fluxExploitation
     */
    public function setFluxExploitation($fluxExploitation) {
        $this->fluxExploitation = $fluxExploitation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResultatFinancier() {
        return $this->resultatFinancier;
    }

    /**
     * @param mixed $resultatFinancier
     */
    public function addResultatFinancier($resultatFinancier) {
        $this->resultatFinancier += $resultatFinancier;

        return $this;
    }

    /**
     * @param mixed $resultatFinancier
     */
    public function setResultatFinancier($resultatFinancier) {
        $this->resultatFinancier = $resultatFinancier;

        return $this;
    }

    /**
     * @return int
     */
    public function getResultatExceptionnel() {
        return $this->resultatExceptionnel;
    }

    /**
     * @return int
     */
    public function getSubventionNonRemboursable() {
        return $this->subventionNonRemboursable;
    }

    /**
     * @param int $subventionNonRemboursable
     */
    public function addSubventionNonRemboursable($subventionNonRemboursable) {
        $this->subventionNonRemboursable += $subventionNonRemboursable;

        return $this;
    }

    /**
     * @param int $subventionNonRemboursable
     */
    public function setSubventionNonRemboursable($subventionNonRemboursable) {
        $this->subventionNonRemboursable = $subventionNonRemboursable;

        return $this;
    }

    /**
     * @return int
     */
    public function getDecaissement() {
        return $this->decaissement;
    }

    /**
     * @param int $decaissement
     */
    public function addDecaissement($decaissement) {
        $this->decaissement += $decaissement;

        return $this;
    }

    /**
     * @param int $decaissement
     */
    public function setDecaissement($decaissement) {
        $this->decaissement = $decaissement;

        return $this;
    }

    /**
     * @return int
     */
    public function getFluxActivite() {
        return $this->fluxActivite;
    }

    /**
     * @param int $fluxActivite
     */
    public function setFluxActivite($fluxActivite) {
        $this->fluxActivite = $fluxActivite;

        return $this;
    }

    /**
     * @return int
     */
    public function getInvestissement() {
        return $this->investissement;
    }

    /**
     * @param int $investissement
     */
    public function addInvestissement($investissement) {
        $this->investissement += $investissement;

        return $this;
    }

    /**
     * @param int $investissement
     */
    public function setInvestissement($investissement) {
        $this->investissement = $investissement;

        return $this;
    }

    /**
     * @return int
     */
    public function getVariationDetteFournisseurImmo() {
        return $this->variationDetteFournisseurImmo;
    }

    /**
     * @param int $variationDetteFournisseurImmo
     */
    public function setVariationDetteFournisseurImmo($variationDetteFournisseurImmo) {
        $this->variationDetteFournisseurImmo = $variationDetteFournisseurImmo;

        return $this;
    }

    /**
     * @return int
     */
    public function getFreeCashFlow() {
        return $this->freeCashFlow;
    }

    /**
     * @param int $freeCashFlow
     */
    public function setFreeCashFlow($freeCashFlow) {
        $this->freeCashFlow = $freeCashFlow;

        return $this;
    }

    /**
     * @return int
     */
    public function getAugmentationCapital() {
        return $this->augmentationCapital;
    }

    /**
     * @param int $augmentationCapital
     */
    public function addAugmentationCapital($augmentationCapital) {
        $this->augmentationCapital += $augmentationCapital;

        return $this;
    }

    /**
     * @param int $augmentationCapital
     */
    public function setAugmentationCapital($augmentationCapital) {
        $this->augmentationCapital = $augmentationCapital;

        return $this;
    }

    /**
     * @return int
     */
    public function getVariationCompteCourant() {
        return $this->variationCompteCourant;
    }

    /**
     * @param int $variationCompteCourant
     */
    public function addVariationCompteCourant($variationCompteCourant) {
        $this->variationCompteCourant += $variationCompteCourant;

        return $this;
    }

    /**
     * @param int $variationCompteCourant
     */
    public function setVariationCompteCourant($variationCompteCourant) {
        $this->variationCompteCourant = $variationCompteCourant;

        return $this;
    }

    /**
     * @return int
     */
    public function getVariationEmprunt() {
        return $this->variationEmprunt;
    }

    /**
     * @param int $variationEmprunt
     */
    public function addVariationEmprunt($variationEmprunt) {
        $this->variationEmprunt += $variationEmprunt;

        return $this;
    }

    /**
     * @param int $variationEmprunt
     */
    public function setVariationEmprunt($variationEmprunt) {
        $this->variationEmprunt = $variationEmprunt;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubventionRemboursable() {
        return $this->subventionRemboursable;
    }

    /**
     * @param int $subventionRemboursable
     */
    public function addSubventionRemboursable($subventionRemboursable) {
        $this->subventionRemboursable += $subventionRemboursable;

        return $this;
    }

    /**
     * @param int $subventionRemboursable
     */
    public function setSubventionRemboursable($subventionRemboursable) {
        $this->subventionRemboursable = $subventionRemboursable;

        return $this;
    }

    /**
     * @return int
     */
    public function getDividende() {
        return $this->dividende;
    }

    /**
     * @param int $dividende
     */
    public function addDividende($dividende) {
        $this->dividende += $dividende;

        return $this;
    }

    /**
     * @param int $dividende
     */
    public function setDividende($dividende) {
        $this->dividende = $dividende;

        return $this;
    }

    /**
     * @return int
     */
    public function getFluxFinancement() {
        return $this->fluxFinancement;
    }

    /**
     * @param int $fluxFinancement
     */
    public function setFluxFinancement($fluxFinancement) {
        $this->fluxFinancement = $fluxFinancement;

        return $this;
    }

    /**
     * @return int
     */
    public function getFluxDeTresorerie() {
        return $this->fluxDeTresorerie;
    }

    /**
     * @param int $fluxDeTresorerie
     */
    public function setFluxDeTresorerie($fluxDeTresorerie) {
        $this->fluxDeTresorerie = $fluxDeTresorerie;

        return $this;
    }

    /**
     * @return int
     */
    public function getTresorerieCloture() {
        return $this->tresorerieCloture;
    }

    /**
     * @param int $tresorerieCloture
     */
    public function setTresorerieCloture($tresorerieCloture) {
        $this->tresorerieCloture = $tresorerieCloture;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return FluxTresorerie
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

}
