<?php

namespace BPBundle\Bilan;

use Doctrine\Common\Collections\ArrayCollection;

class BuilderFluxTresorerie {

    protected $fluxDeTresorerieParExercice;

    public function __construct() {
        $this->fluxDeTresorerieParExercice = new ArrayCollection();
    }

    public function init($infoMensuel, $infoBilanMensuel, $exercices, $investissements, $financements, $codeCountry) {
        // PNL
        $numeroExerciceEnCours = 1;
        $nbInfoMensuel = count($infoMensuel) - 1;
        foreach ($infoMensuel as $key => $info) {
            $date = clone $info->getDate();
            $fluxTresorerie = new FluxTresorerie();
            $fluxTresorerie->setDate($date);
            if ($info->getNumExercice() > $numeroExerciceEnCours) {
                $numeroExerciceEnCours++;
            }

            // EBE
            $fluxTresorerie->setEbe($info->getEbe());
            // Résultat financier
            $fluxTresorerie->setResultatFinancier($info->getResultatFinancierMensuel());
            // Subvention non remboursable
            $fluxTresorerie->setSubventionNonRemboursable($info->getSubventionNonRemboursable());
            // Décaissement IS step 1
            $fluxTresorerie->setDecaissement($info->getImpotSurSociete());

            // Dividendes
            foreach ($financements as $financement) {
                if ($financement->getChargeLabel()->getIsDividende()) {
                    if ($financement->getCreatedAt()->format('Y-m') == $info->getDate()->format('Y-m')) {
                        $fluxTresorerie->addDividende($financement->getAmount() * -1);
                    }
                }
            }

            $this->addFluxDeTresorerieParExerciceInfoMensuel($fluxTresorerie, $numeroExerciceEnCours);
        }

        // Bilan
        $numeroExerciceEnCours = 1;
        $nbInfoBilanMensuel = count($infoBilanMensuel) - 1;
        foreach ($infoBilanMensuel as $key => $info) {
            if ($info->getNumExercice() > $numeroExerciceEnCours) {
                $numeroExerciceEnCours++;
            }

            $nouveauFluxTresorerie = false;
            if ($this->fluxDeTresorerieParExercice->containsKey($key)) {
                $fluxTresorerie = $this->fluxDeTresorerieParExercice->get($key);
            } else {
                $nouveauFluxTresorerie = true;
                $fluxTresorerie = new FluxTresorerie();
                $date = clone $info->getDate();
                $fluxTresorerie->setDate($date);
            }

            $infoBilanPrecedent = null;
            if ($key > 0) {
                $infoBilanPrecedent = $infoBilanMensuel[$key - 1];
            }

            // Variation du BFR
            $fluxTresorerie->setVariationBfr($this->calculVariationBfr($info, $infoBilanPrecedent));

            // Investissements
            foreach ($investissements as $investissement) {
                $dateClone = clone $investissement->getDate();
                $dateClone->modify('first day of this month');
                if ($dateClone == $info->getDate()) {
                    $fluxTresorerie->addInvestissement($investissement->getAmount() * -1);
                }
            }
            // Variation dette fournisseurs immo
            $fluxTresorerie->setVariationDetteFournisseurImmo($this->calculVariationDetteFournisseurImmo($info, $infoBilanPrecedent));

            //Variation CA
            $fluxTresorerie->setCA($this->calculVariationCA($info, $infoBilanPrecedent));

            //Variation Achats (Directs + Variables + Charges externes)
            $fluxTresorerie->setAchatsHorsStock($this->calculVariationAchatsHorsStock($info, $infoBilanPrecedent));

            //Variation Achats (Stocks)
            $fluxTresorerie->setAchatStock($this->calculVariationAchatStock($info, $infoBilanPrecedent));

            //Variation Personnel
            $fluxTresorerie->setPersonnel($this->calculVariationPersonnelle($info, $infoBilanPrecedent));

            //Variation Impôts et taxes
            $fluxTresorerie->setImpotsEtTaxes($this->calculVariationImpotsEtTaxes($info, $infoBilanPrecedent));

            // Décaissement IS step 2 (final)
            $fluxTresorerie->setDecaissement($fluxTresorerie->getDecaissement() + $this->calculVariationIs($info, $infoBilanPrecedent));

            // Augmentation du capital
            $fluxTresorerie->addAugmentationCapital($this->calculVariationAugmentationCapital($info, $infoBilanPrecedent));

            // Variation compte courant
            $fluxTresorerie->addVariationCompteCourant($this->calculVariationCompteCourant($info, $infoBilanPrecedent));

            // Variation emprunt
            $fluxTresorerie->addVariationEmprunt($this->calculVariationEmprunt($info, $infoBilanPrecedent));

            // Subvention remboursable
            $fluxTresorerie->addSubventionRemboursable($this->calculVariationSubventionRemboursable($info, $infoBilanPrecedent));

            if ($nouveauFluxTresorerie) {
                $this->addFluxDeTresorerieParExerciceInfoMensuel($fluxTresorerie, $numeroExerciceEnCours);
            }
        }

        // Flux d'exploitation / Flux d'activité / Flux financement / Flux trésorerie / Trésorerie cloture
        foreach ($this->fluxDeTresorerieParExercice as $key => $fluxTresorerie) {
            // Flux d'exploitation
            $fluxTresorerie->setFluxExploitation($fluxTresorerie->getEbe() + $fluxTresorerie->getVariationBfr());
            $fluxTresorerie->setFluxActivite($fluxTresorerie->getFluxExploitation() +
                    $fluxTresorerie->getResultatFinancier() +
                    $fluxTresorerie->getResultatExceptionnel() +
                    $fluxTresorerie->getSubventionNonRemboursable() +
                    $fluxTresorerie->getDecaissement());
            $fluxTresorerie->setFreeCashFlow($fluxTresorerie->getFluxActivite() +
                    $fluxTresorerie->getInvestissement() +
                    $fluxTresorerie->getVariationDetteFournisseurImmo());
            $fluxTresorerie->setFluxFinancement($fluxTresorerie->getAugmentationCapital() +
                    $fluxTresorerie->getVariationCompteCourant() +
                    $fluxTresorerie->getVariationEmprunt() +
                    $fluxTresorerie->getSubventionRemboursable() +
                    $fluxTresorerie->getDividende());
            $fluxTresorerie->setFluxDeTresorerie($fluxTresorerie->getFreeCashFlow() + $fluxTresorerie->getFluxFinancement());

            if ($key > 0) {
                $tresorerieClotureMoisAvant = $this->fluxDeTresorerieParExercice[$key - 1];
                $fluxTresorerie->setTresorerieCloture($tresorerieClotureMoisAvant->getTresorerieCloture() + $fluxTresorerie->getFluxDeTresorerie());
            } else {
                $fluxTresorerie->setTresorerieCloture($fluxTresorerie->getFluxDeTresorerie());
            }
        }
    }

    private function calculVariationBfr(InfoBilanMensuel $info, $infoBilanPrecedent) {
        ;
        $bfr = $info->getBfr();
        $sommeBfr = ($bfr->getStock() ? $bfr->getStock()->getSommeValeur() : 0) +
                ($bfr->getClient() ? $bfr->getClient()->getSommeValeur() : 0) +
                ($bfr->getAutreCreance() ? $bfr->getAutreCreance()->getSommeValeur() : 0) +
                ($bfr->getAutreCreanceChargesExternes() ? $bfr->getAutreCreanceChargesExternes()->getSommeValeur() : 0) +
                ($bfr->getFournisseur() ? $bfr->getFournisseur()->getSommeValeur() : 0) +
                ($bfr->getFournisseurChargesExternes() ? $bfr->getFournisseurChargesExternes()->getSommeValeur() : 0) +
                ($bfr->getDetteSociale() ? $bfr->getDetteSociale()->getSommeValeur() : 0) +
                ($bfr->getDetteFiscale() ? $bfr->getDetteFiscale() : 0) +
                ($bfr->getAutreDette() ? $bfr->getAutreDette()->getSommeValeur() : 0);
        $bfr->setSommeValeur($sommeBfr);

        $sommeBfrPrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeBfrPrecedent = ($bfr->getStock() ? $bfr->getStock()->getSommeValeur() : 0) +
                    ($bfr->getClient() ? $bfr->getClient()->getSommeValeur() : 0) +
                    ($bfr->getAutreCreance() ? $bfr->getAutreCreance()->getSommeValeur() : 0) +
                    ($bfr->getAutreCreanceChargesExternes() ? $bfr->getAutreCreanceChargesExternes()->getSommeValeur() : 0) +
                    ($bfr->getFournisseur() ? $bfr->getFournisseur()->getSommeValeur() : 0) +
                    ($bfr->getFournisseurChargesExternes() ? $bfr->getFournisseurChargesExternes()->getSommeValeur() : 0) +
                    ($bfr->getDetteSociale() ? $bfr->getDetteSociale()->getSommeValeur() : 0) +
                    ($bfr->getDetteFiscale() ? $bfr->getDetteFiscale() : 0) +
                    ($bfr->getAutreDette() ? $bfr->getAutreDette()->getSommeValeur() : 0);
            $bfr->setSommeValeur($sommeBfrPrecedent);
        }

        return $sommeBfrPrecedent - $sommeBfr;
    }

    private function calculVariationAchatStock(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $bfr = $info->getBfr();
        $sommeStock = $bfr->getStock() ? $bfr->getStock()->getSommeValeur() : 0;


        $sommeStockPrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeStockPrecedent = $bfr->getStock() ? $bfr->getStock()->getSommeValeur() : 0;
        }

        return $sommeStockPrecedent - $sommeStock;
    }

    private function calculVariationPersonnelle(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $bfr = $info->getBfr();
        $sommeDetteSociale = $bfr->getDetteSociale() ? $bfr->getDetteSociale()->getSommeValeur() : 0;


        $sommeDetteSocialePrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeDetteSocialePrecedent = $bfr->getDetteSociale() ? $bfr->getDetteSociale()->getSommeValeur() : 0;
        }


        $chargePersonnel = $info->getChargePersonnel();
        $cice = $info->getCice();
        $chargePatronale = $info->getChargePatronale();

        return $chargePersonnel + $cice + $chargePatronale + $sommeDetteSocialePrecedent - $sommeDetteSociale;
    }

    private function calculVariationImpotsEtTaxes(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $bfr = $info->getBfr();
        $sommeAutresCreances = $bfr->getAutreCreance() ? $bfr->getAutreCreance()->getSommeValeur() : 0;
        $sommeDettesFiscales = $bfr->getDetteFiscale() ? $bfr->getDetteFiscale() : 0;
        $sommeAutresDettes = $bfr->getAutreDette() ? $bfr->getAutreDette()->getSommeValeur() : 0;


        $sommeAutresCreancesPrecedent = 0;
        $sommeDettesFiscalesPrecedent = 0;
        $sommeAutresDettesPrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeAutresCreancesPrecedent = $bfr->getAutreCreance() ? $bfr->getAutreCreance()->getSommeValeur() : 0;
            $sommeDettesFiscalesPrecedent = $bfr->getDetteFiscale() ? $bfr->getDetteFiscale() : 0;
            $sommeAutresDettesPrecedent = $bfr->getAutreDette() ? $bfr->getAutreDette()->getSommeValeur() : 0;
        }


        $impotsEtTaxes = $info->getImpotsEtTaxes();

        return $impotsEtTaxes + $sommeAutresCreancesPrecedent - $sommeAutresCreances + $sommeDettesFiscalesPrecedent - $sommeDettesFiscales + $sommeAutresDettesPrecedent - $sommeAutresDettes;
    }

    private function calculVariationCA(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $bfr = $info->getBfr();
        $sommeClient = $bfr->getClient() ? $bfr->getClient()->getSommeValeur() : 0;


        $sommeClientPrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeClientPrecedent = $bfr->getClient() ? $bfr->getClient()->getSommeValeur() : 0;
        }

        $ca = $info->getCA();

        return $ca + $sommeClientPrecedent - $sommeClient;
    }

    private function calculVariationAchatsHorsStock(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $bfr = $info->getBfr();
        $sommeFournisseur = $bfr->getFournisseur() ? $bfr->getFournisseur()->getSommeValeur() : 0;
        $sommeFournisseur += $bfr->getFournisseurChargesExternes() ? $bfr->getFournisseurChargesExternes()->getSommeValeur() : 0;


        $sommeFournisseurPrecedent = 0;
        if ($infoBilanPrecedent) {
            $bfr = $infoBilanPrecedent->getBfr();
            $sommeFournisseurPrecedent = $bfr->getFournisseur() ? $bfr->getFournisseur()->getSommeValeur() : 0;
            $sommeFournisseurPrecedent += $bfr->getFournisseurChargesExternes() ? $bfr->getFournisseurChargesExternes()->getSommeValeur() : 0;
        }

        return $info->getAchat() + $info->getCommission() + $info->getAchatVariable() + $info->getChargeVariableSurChargeExterne() + $sommeFournisseurPrecedent - $sommeFournisseur;
    }

    private function calculVariationIs(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getIs() ? $info->getIs()->getDetteFinale() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getIs() ? $infoBilanPrecedent->getIs()->getDetteFinale() : 0);
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function calculVariationChargesTNS(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getChargesTNS() ? $info->getChargesTNS()->getDetteFinale() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getChargesTNS() ? $infoBilanPrecedent->getChargesTNS()->getDetteFinale() : 0);
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function calculVariationAugmentationCapital(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getCapitalSocial() ? $info->getCapitalSocial()->getSommeCapitalSocial() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getCapitalSocial() ? $infoBilanPrecedent->getCapitalSocial()->getSommeCapitalSocial() : 0);
        }

        return ($sommeMoisPrecedent - $somme) * -1;
    }

    private function calculVariationCompteCourant(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getTresorerie() ? $info->getTresorerie()->getSoldeCompteCourantAssocie() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getTresorerie() ? $infoBilanPrecedent->getTresorerie()->getSoldeCompteCourantAssocie() : 0);
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function calculVariationEmprunt(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getTresorerie() ? $info->getTresorerie()->getSommeEmpruntBancaire() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getTresorerie() ? $infoBilanPrecedent->getTresorerie()->getSommeEmpruntBancaire() : 0);
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function calculVariationSubventionRemboursable(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = ($info->getTresorerie() ? $info->getTresorerie()->getSommeSubventionRemboursable() : 0);

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = ($infoBilanPrecedent->getTresorerie() ? $infoBilanPrecedent->getTresorerie()->getSommeSubventionRemboursable() : 0);
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function calculVariationDetteFournisseurImmo(InfoBilanMensuel $info, $infoBilanPrecedent) {
        $somme = $info->getDetteFournisseurImmo();

        $sommeMoisPrecedent = 0;
        if ($infoBilanPrecedent) {
            $sommeMoisPrecedent = $infoBilanPrecedent->getDetteFournisseurImmo();
        }

        return $sommeMoisPrecedent - $somme;
    }

    private function addFluxDeTresorerieParExerciceInfoMensuel($fluxTresorerie, $numeroExerciceEnCours) {
        $fluxTresorerie->setNumExercice($numeroExerciceEnCours);
        $this->fluxDeTresorerieParExercice->add($fluxTresorerie);
    }

    /**
     * @return mixed
     */
    public function getTableauDeFluxDeTresorerie() {
        return $this->fluxDeTresorerieParExercice;
    }

}
