<?php

namespace BPBundle\Bilan;

use BPBundle\Pnl\Helper;
use Doctrine\Common\Collections\ArrayCollection;

class DispatchBfr {

    protected $infoProduits;
    protected $infoCharges;
    protected $infoBfr;
    protected $dateDebutActivite;
    protected $dateFinActivite;
    protected $tva;
    protected $tvaSurEncaissement;

    /**
     * Stock constructor.
     * @param $infoProduits
     */
    public function __construct($infoProduits, $infoCharges, $infoBfr, $dateDebutActivite, $dateFinActivite, $tva, $tvaSurEncaissement) {
        $this->infoProduits = $infoProduits;
        $this->infoCharges = $infoCharges;
        $this->infoBfr = $infoBfr;
        $this->dateDebutActivite = $dateDebutActivite;
        $this->dateFinActivite = $dateFinActivite;
        $this->tva = $tva;
        $this->tvaSurEncaissement = $tvaSurEncaissement;
    }

    /**
     * @param $element = stock ou client
     * @param $tabDetailElementParDate
     * @param $dateDuMois
     * @return mixed
     */
    public function getValeurBfrElement($element, $tabDetailElementParDate, $dateDuMois, $tousLesExercices = null, $stockOrganiserParDate = null, $clientOrganiserParDate = null, $fournisseurOrganiserParDate = null) {
        foreach ($this->infoProduits as $index => $produit) {
            $delai = 0;
            switch ($element) {
                case Helper::POSTE_STOCK:
                    $delai = $this->infoBfr->getStock();
                    if ($produit->getProduit()->hasCustomProductStockSeasons()) {
                        $delai = $produit->getProduit()->getProductStockSeasonByDate($dateDuMois)->getSaisonNbStockDays();
                    }
                    $bfrProduit = new StockProduit($produit, $delai, $dateDuMois, $this->dateFinActivite, $tousLesExercices);
                    break;
                case Helper::POSTE_CLIENT:
                    $delai = $this->infoBfr->getCustomer();
                    $bfrProduit = new ClientProduit($produit, $delai, $dateDuMois, $this->dateFinActivite, $this->tva, $tousLesExercices);
                    break;
                case Helper::POSTE_AUTRE_CREANCE:
                    $acompte = $this->infoBfr->getAcpteProvider();
                    $delaiPaiementFournisseur = $this->infoBfr->getProvider();
                    $bfrProduit = new AutreCreanceProduit($produit, $acompte, $delaiPaiementFournisseur, $dateDuMois, $this->tva, $this->tvaSurEncaissement, $stockOrganiserParDate, $tousLesExercices, $fournisseurOrganiserParDate);
                    break;
                case Helper::POSTE_AUTRE_DETTE:
                    $acompte = $this->infoBfr->getAcpteCustomer();
                    $delaiPaiementFournisseur = $this->infoBfr->getCustomer();
                    $bfrProduit = new AutreDetteProduit($produit, $acompte, $delaiPaiementFournisseur, $dateDuMois, $this->tva, $this->tvaSurEncaissement, $tousLesExercices, $clientOrganiserParDate);
                    break;
                case Helper::POSTE_FOURNISSEUR:
                    $delaiPaiementFournisseur = $this->infoBfr->getProvider();
                    $bfrProduit = new FournisseurProduit($produit, $delaiPaiementFournisseur, $dateDuMois, $this->dateFinActivite, $this->tva, $tousLesExercices, $stockOrganiserParDate);
                    break;
            }

            $key = $bfrProduit->getDateDuMois()->format('Y-m-d');
            if (array_key_exists($key, $tabDetailElementParDate)) {
                $elementBfr = $tabDetailElementParDate[$key];
            } else {
                switch ($element) {
                    case Helper::POSTE_STOCK:
                        $elementBfr = new Stock();
                        break;
                    case Helper::POSTE_CLIENT:
                        $elementBfr = new Client();
                        if ($bfrProduit->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setProduitARecevoir(true);
                        }
                        break;
                    case Helper::POSTE_AUTRE_CREANCE:
                        $dateMoisPrecedent = clone $bfrProduit->getDateDuMois();
                        $dateMoisPrecedent->sub(new \DateInterval('P1M'));
                        $keyMoisPrecedent = $dateMoisPrecedent->format('Y-m-d');
                        $sommeFournisseurChargeMoisPrecedent = 0;
                        if (array_key_exists($keyMoisPrecedent, $tabDetailElementParDate)) {
                            $sommeFournisseurChargeMoisPrecedent += $tabDetailElementParDate[$keyMoisPrecedent]->getSommeValeur();
                        }
                        $elementBfr = new AutreCreance($sommeFournisseurChargeMoisPrecedent);
                        if ($bfrProduit->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setAutreCreanceAVenir(true);
                        }
                        break;
                    case Helper::POSTE_AUTRE_DETTE:
                        $dateMoisPrecedent = clone $bfrProduit->getDateDuMois();
                        $dateMoisPrecedent->sub(new \DateInterval('P1M'));
                        $keyMoisPrecedent = $dateMoisPrecedent->format('Y-m-d');
                        $sommeFournisseurChargeMoisPrecedent = 0;
                        if (array_key_exists($keyMoisPrecedent, $tabDetailElementParDate)) {
                            $sommeFournisseurChargeMoisPrecedent += $tabDetailElementParDate[$keyMoisPrecedent]->getSommeValeur();
                        }
                        $elementBfr = new AutreDette($sommeFournisseurChargeMoisPrecedent);
                        if ($bfrProduit->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setAutreDetteAVenir(true);
                        }
                        break;
                    case Helper::POSTE_FOURNISSEUR:
                        $elementBfr = new Fournisseur();
                        if ($bfrProduit->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setChargeAPayer(true);
                        }
                        break;
                }
            }

            $elementBfr->addDetailPoste($bfrProduit);
            $tabDetailElementParDate[$key] = $elementBfr;
        }

        return $tabDetailElementParDate;
    }

    public function getValeurBfrElementCharges($element, $tabDetailElementParDate, $dateDuMois, $tousLesExercices, $fournisseurChargesOrganiserParDate = null) {
        foreach ($this->infoCharges as $infoCharge) {
            switch ($element) {
                case Helper::POSTE_AUTRE_CREANCE:

                    $delaiPaiementFournisseur = $this->infoBfr->getProvider();
                    $bfrCharge = new AutreCreanceCharge($infoCharge, $delaiPaiementFournisseur, $dateDuMois, $this->tva, $this->tvaSurEncaissement, $tousLesExercices, $fournisseurChargesOrganiserParDate);

                    break;
                case Helper::POSTE_FOURNISSEUR:

                    $delaiPaiementFournisseur = $this->infoBfr->getProvider();

                    $dateMoisPrecedent = clone $dateDuMois;
                    $dateMoisPrecedent->sub(new \DateInterval('P1M'));
                    $keyMoisPrecedent = $dateMoisPrecedent->format('Y-m-d');
                    $sommeFournisseurChargeMoisPrecedent = 0;
                    if (array_key_exists($keyMoisPrecedent, $tabDetailElementParDate)) {
                        $sommeFournisseurChargeMoisPrecedent -= $tabDetailElementParDate[$keyMoisPrecedent]->getSommeValeurCharge($infoCharge->getId());
                    }

                    $bfrCharge = new FournisseurCharge($infoCharge, $delaiPaiementFournisseur, $sommeFournisseurChargeMoisPrecedent, $dateDuMois, $this->tva, $tousLesExercices);
                    break;
            }

            $key = $bfrCharge->getDateDuMois()->format('Y-m-d');
            if (array_key_exists($key, $tabDetailElementParDate)) {
                $elementBfr = $tabDetailElementParDate[$key];
            } else {
                switch ($element) {

                    case Helper::POSTE_AUTRE_CREANCE:
                        $dateMoisPrecedent = clone $bfrCharge->getDateDuMois();
                        $dateMoisPrecedent->sub(new \DateInterval('P1M'));
                        $keyMoisPrecedent = $dateMoisPrecedent->format('Y-m-d');
                        $sommeFournisseurChargeMoisPrecedent = 0;
                        if (array_key_exists($keyMoisPrecedent, $tabDetailElementParDate)) {
                            $sommeFournisseurChargeMoisPrecedent += $tabDetailElementParDate[$keyMoisPrecedent]->getSommeValeur();
                        }
                        $elementBfr = new AutreCreance($sommeFournisseurChargeMoisPrecedent);
                        if ($bfrCharge->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setAutreCreanceAVenir(true);
                        }
                        break;
                    case Helper::POSTE_FOURNISSEUR:
                        $elementBfr = new Fournisseur();
                        if ($bfrCharge->getDateDuMois() > $this->dateFinActivite) {
                            $elementBfr->setChargeAPayer(true);
                        }
                        break;
                }
            }
            $elementBfr->addDetailPosteCharge($bfrCharge);
            $tabDetailElementParDate[$key] = $elementBfr;
        }

        return $tabDetailElementParDate;
    }

}
