<?php

namespace BPBundle\Entity;

use BPBundle\Bilan\ActifImmobilise;
use BPBundle\Bilan\BfrExploitation;
use BPBundle\Bilan\BuilderFluxTresorerie;
use BPBundle\Bilan\CapitalSocial;
use BPBundle\Bilan\CategorieImmobilisation;
use BPBundle\Bilan\ChargesTNSBilan;
use BPBundle\Bilan\DetailDetteSociale;
use BPBundle\Bilan\DetteSociale;
use BPBundle\Bilan\DispatchBfr;
use BPBundle\Bilan\DispatchDetteFiscale;
use BPBundle\Bilan\DispatchDetteSociale;
use BPBundle\Bilan\DispatcherImmobilisation;
use BPBundle\Bilan\InfoBilanMensuel;
use BPBundle\Bilan\IsBilan;
use BPBundle\Bilan\Reserve;
use BPBundle\Bilan\Tresorerie;
use BPBundle\Pnl\Activite;
use BPBundle\Pnl\ChargePersonnel;
use BPBundle\Pnl\ChargesTNSExercice;
use BPBundle\Pnl\ChargesTNSMensuelles;
use BPBundle\Pnl\CoutVariable;
use BPBundle\Pnl\Helper;
use BPBundle\Pnl\ImpotEtTaxe;
use BPBundle\Pnl\ISExercice;
use BPBundle\Pnl\ISMensuel;
use BPBundle\Pnl\ResultatFinancier;
use UserBundle\Entity\Document;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PromotionBundle\Entity\Catalog;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessPlan
 *
 * @ORM\Table(name="bp_bp")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\BusinessPlanRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class BusinessPlan {

    public function __construct() {
        $this->charges = new \Doctrine\Common\Collections\ArrayCollection();
        $this->staffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->investissements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fundings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exercices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->saisonnalites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->infoMensuel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->infoBilanMensuel = new \Doctrine\Common\Collections\ArrayCollection();

        // Si aucune saisonnalité n'est définie
        if ($this->saisonnalites->isEmpty()) {
            while ($this->saisonnalites->count() < 12) {
                $this->addSaisonnalite(new Saisonnalite());
            }
        }

        $this->setInfoBfr(new Bfr());
        $this->setHash(uniqid('bp_'));
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=150, nullable=true)
     */
    protected $state;

    /**
     * @var array
     *
     * @ORM\Column(name="steps", type="json_array", nullable=false)
     */
    protected $steps = [];

    /**
     *
     * @Assert\File(
     *     mimeTypes = {"application/pdf" },
     *     mimeTypesMessage = "Extensions autorisées pdf",
     * )
     */
    protected $file;

    /**
     * @ORM\column(length=128, unique=true)
     */
    protected $hash;

    /**
     * @var Catalog
     * @ORM\ManyToOne(targetEntity="PromotionBundle\Entity\Catalog", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $catalog;

    /**
     * @var Document
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Document", mappedBy="businessPlan", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $documents;


    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="businessPlans", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var Information
     * @ORM\OneToOne(targetEntity="Information", mappedBy="businessPlan")
     */
    private $information;

    /**
     * @var CustomWriting
     * @ORM\OneToOne(targetEntity="CustomWriting", mappedBy="businessPlan")
     */
    private $customWriting;

    /**
     * @var MarketStudy
     * @ORM\OneToOne(targetEntity="MarketStudy", mappedBy="businessPlan")
     */
    private $marketStudy;

    /**
     * @var Charge
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Charge", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $charges;

    /**
     * @var Staff
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Staff", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $staffs;

    /**
     * @var Investissement
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Investissement", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $investissements;

    /**
     * @var Funding
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Funding", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $fundings;

    /**
     * @var Exercice
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Exercice", mappedBy="businessPlan", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $exercices;

    /**
     * @var Produit
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Produit", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $produits;

    /**
     * @var Saisonnalite
     * @ORM\OneToMany(targetEntity="Saisonnalite", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $saisonnalites;

    /**
     * @var Bfr
     * @ORM\OneToOne(targetEntity="Bfr", mappedBy="businessPlan", cascade={"persist", "remove"})
     */
    private $infoBfr;

    /**
     * @var CommandeCatalog
     * @ORM\OneToMany(targetEntity="PromotionBundle\Entity\CommandeCatalog", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $commandeCatalogs;

    protected $infoMensuel;
    protected $infoAnnuel;
    protected $effetVolumeMixPrix;
    protected $infoBilanMensuel;
    protected $infoBilanAnnuel;

    /**
     * @ORM\OneToOne(targetEntity="BPBundle\Entity\Service",mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $service;

    /**
     * @var BackBundle\Entity\JoorneyOrder
     * @ORM\OneToMany(targetEntity="BackBundle\Entity\JoorneyOrder", mappedBy="businessPlan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $orders;

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function upload() {
        if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        $this->document = $this->file->getClientOriginalName();
    }

    public function getUploadDir() {
        return "uploads/b_plan";
    }

    public function getWebPath() {
        if (null === $this->document) {
            return null;
        }

        return $this->getUploadDir() . '/' . $this->document;
    }

    public function getUploadRootDir() {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return BusinessPlan
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return BusinessPlan
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return BusinessPlan
     */
    public function setHash($hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set catalog
     *
     * @param Catalog $catalog
     *
     * @return BusinessPlan
     */
    public function setCatalog(Catalog $catalog) {
        $this->catalog = $catalog;
        return $this;
    }

    /**
     * Get catalog
     *
     * @return Catalog
     */
    public function getCatalog() {
        return $this->catalog;
    }

    /**
     * Set documents
     *
     * @param Document $documents
     *
     * @return BusinessPlan
     */
    public function setDocuments(Document $documents) {
        $this->documents = $documents;
        return $this;
    }

    /**
     * Get documents
     *
     * @return Document
     */
    public function getDocuments() {
        return $this->documents;
    }
    
    /**
     * Get wording documents
     *
     * @return Document
     */
    public function getWordingDocuments() {
        if ($this->documents->isEmpty()) {
            return $this->documents;
        }

        return $this->documents->filter(function($doc) {
            return $doc->getType() === Document::DOCUMENT_TYPE_WORDING;
        });
    }

    /**
     * Get advice summary documents
     *
     * @return Document
     */
    public function getAdviceSummaryDocuments() {
        if ($this->documents->isEmpty()) {
            return $this->documents;
        }

        return $this->documents->filter(function($doc) {
            return $doc->getType() === Document::DOCUMENT_TYPE_ADVICE;
        });
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BusinessPlan
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BusinessPlan
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return BusinessPlan
     */
    public function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt() {
        return $this->deletedAt;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return BusinessPlan
     */
    public function setUser(\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set information
     *
     * @param \BPBundle\Entity\Information $information
     *
     * @return BusinessPlan
     */
    public function setInformation(\BPBundle\Entity\Information $information = null) {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return \BPBundle\Entity\Information
     */
    public function getInformation() {
        return $this->information;
    }

    public function getDisplayName() {
        if ($this->getInformation()) {
            return strtoupper($this->getInformation()->getNameCorporate());
        }

        return "Projet Vierge" . ($this->getIsNew() ? ' (Nouveau)' : '');
    }

    /**
     * Add charge
     *
     * @param \BPBundle\Entity\Charge $charge
     *
     * @return BusinessPlan
     */
    public function addCharge(\BPBundle\Entity\Charge $charge) {
        $this->charges[] = $charge;

        return $this;
    }

    /**
     * Remove charge
     *
     * @param \BPBundle\Entity\Charge $charge
     */
    public function removeCharge(\BPBundle\Entity\Charge $charge) {
        $this->charges->removeElement($charge);
    }

    /**
     * Get charges
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharges() {
        $iterator = $this->charges->getIterator();
        $iterator->uasort(function ($a, $b) {
            if ($a->getChargeLabel() != null && $b->getChargeLabel() != null) {
                return ($a->getChargeLabel()->getDisplayOrder() > $b->getChargeLabel()->getDisplayOrder()) ? +1 : -1;
            } else {
                return (0);
            }
        });
        $collection = new \Doctrine\Common\Collections\ArrayCollection(iterator_to_array($iterator));
        return $collection;
    }

    /**
     * Add staff
     *
     * @param \BPBundle\Entity\Staff $staff
     *
     * @return BusinessPlan
     */
    public function addStaff(\BPBundle\Entity\Staff $staff) {
        $this->staffs[] = $staff;

        return $this;
    }

    /**
     * Remove staff
     *
     * @param \BPBundle\Entity\Staff $staff
     */
    public function removeStaff(\BPBundle\Entity\Staff $staff) {
        $this->staffs->removeElement($staff);
    }

    /**
     * Get staffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStaffs() {
        return $this->staffs;
    }

    /**
     * Add investissement
     *
     * @param \BPBundle\Entity\Investissement $investissement
     *
     * @return BusinessPlan
     */
    public function addInvestissement(\BPBundle\Entity\Investissement $investissement) {
        $this->investissements[] = $investissement;

        return $this;
    }

    /**
     * Remove investissement
     *
     * @param \BPBundle\Entity\Investissement $investissement
     */
    public function removeInvestissement(\BPBundle\Entity\Investissement $investissement) {
        $this->investissements->removeElement($investissement);
    }

    /**
     * Get investissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvestissements() {
        return $this->investissements;
    }

    /**
     * Add funding
     *
     * @param \BPBundle\Entity\Funding $funding
     *
     * @return BusinessPlan
     */
    public function addFunding(\BPBundle\Entity\Funding $funding) {
        $this->fundings[] = $funding;

        return $this;
    }

    /**
     * Remove funding
     *
     * @param \BPBundle\Entity\Funding $funding
     */
    public function removeFunding(\BPBundle\Entity\Funding $funding) {
        $this->fundings->removeElement($funding);
    }

    /**
     * Get fundings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFundings() {
        return $this->fundings;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return BusinessPlan
     */
    public function addStep($step) {
        if (!in_array($step, $this->steps)) {
            array_push($this->steps, $step);
        }

        return $this;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return BusinessPlan
     */
    public function removeStep($step) {
        if (in_array($step, $this->steps)) {
            unset($this->steps[$step]);
        }
        return $this;
    }

    /**
     * Get step
     *
     * @return integer
     */
    public function getSteps() {
        return $this->steps;
    }

    /**
     * Add exercice
     *
     * @param \BPBundle\Entity\Exercice $exercice
     *
     * @return BusinessPlan
     */
    public function addExercice(\BPBundle\Entity\Exercice $exercice) {
        $this->exercices[] = $exercice;
        $exercice->setBusinessPlan($this);

        return $this;
    }

    /**
     * Remove exercice
     *
     * @param \BPBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\BPBundle\Entity\Exercice $exercice) {
        $this->exercices->removeElement($exercice);
        $exercice->setBusinessPlan(null);
    }

    /**
     * Get exercices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercices() {
        return $this->exercices;
    }

    /**
     * Add produit
     *
     * @param \BPBundle\Entity\Produit $produit
     *
     * @return BusinessPlan
     */
    public function addProduit(\BPBundle\Entity\Produit $produit) {
        $this->produits[] = $produit;
        $produit->setBusinessPlan($this);

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \BPBundle\Entity\Produit $produit
     */
    public function removeProduit(\BPBundle\Entity\Produit $produit) {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits() {
        return $this->produits;
    }

    public function getInfoBfr() {
        return $this->infoBfr;
    }

    public function setInfoBfr($infoBfr) {
        $this->infoBfr = $infoBfr;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInfoMensuel() {
        return $this->infoMensuel;
    }

    /**
     * @param \BPBundle\Entity\BpInfoMensuel $infoMensuel
     */
    public function addInfoMensuel($infoMensuel) {
        $this->infoMensuel[] = $infoMensuel;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $infoMensuel
     */
    public function removeInfoMensuel($infoMensuel) {
        $this->infoMensuel->removeElement($infoMensuel);
    }

    /**
     * @return array
     */
    public function getInfoAnnuel() {
        return $this->infoAnnuel;
    }

    /**
     * @return array
     */
    public function setInfoAnnuel($infoAnnuel) {
        $this->infoAnnuel = $infoAnnuel;
        return $this;
    }

    /**
     * @return array
     */
    public function getInfoBilanAnnuel() {
        return $this->infoBilanAnnuel;
    }

    /**
     * @return array
     */
    public function setInfoBilanAnnuel($infoBilanAnnuel) {
        $this->infoBilanAnnuel = $infoBilanAnnuel;
        return $this;
    }

    public function getEffetVolumeMixPrix() {
        return $this->effetVolumeMixPrix;
    }

    public function setEffetVolumeMixPrix($effetVMP) {
        $this->effetVolumeMixPrix = $effetVMP;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInfoBilanMensuel() {
        return $this->infoBilanMensuel;
    }

    /**
     * @param \BPBundle\Entity\BpInfoMensuel $infoBilanMensuel
     */
    public function addInfoBilanMensuel($infoBilanMensuel) {
        $this->infoBilanMensuel[] = $infoBilanMensuel;

        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $infoBilanMensuel
     */
    public function removeInfoBilanMensuel($infoBilanMensuel) {
        $this->infoBilanMensuel->removeElement($infoBilanMensuel);
    }

    public function getSaisonnalites() {
        return $this->saisonnalites;
    }

    public function addSaisonnalite(Saisonnalite $saisonnalite) {
        $this->saisonnalites->add($saisonnalite);
        $saisonnalite->setBusinessPlan($this);
    }

    /**
     * Remove saisonnalite
     *
     * @param \BPBundle\Entity\Saisonnalite $saisonnalite
     */
    public function removeSaisonnalite(\BPBundle\Entity\Saisonnalite $saisonnalite) {
        $this->saisonnalites->removeElement($saisonnalite);
    }

    public function getCustomWriting() {
        return $this->customWriting;
    }

    public function setCustomWriting($customWriting) {
        $this->customWriting = $customWriting;
    }

    public function getMarketStudy() {
        return $this->marketStudy;
    }

    public function setMarketStudy($marketStudy) {
        $this->marketStudy = $marketStudy;
    }

    public function hasAdviceHourLeft() {
        return $this->getService()->getAdviceHour() > 0;
    }

    public function isTrial() {
        return !!$this->getService()->getExpireTrialDate();
    }

    public function isTrialActive() {
        return $this->getService()->getExpireTrialDate() && $this->getService()->getExpireTrialDate() > new DateTime();
    }

    public function isTrialExpired() {
        return $this->getService()->getExpireTrialDate() && $this->getService()->getExpireTrialDate() <= new DateTime();
    }

    public function getIsEditable() {
        return (!$this->getService()->getExpireEditDate() 
        || $this->getService()->getExpireEditDate() && $this->getService()->getExpireEditDate() > new DateTime());
    }

    public function getIsNew() {
        return !in_array(1, $this->getSteps()) || !in_array(2, $this->getSteps());
    }

    public function getIsFinancialCompleted() {
        return !array_diff(range(1, 9), $this->getSteps());
    }
    public function getIsWordingCompleted() {
        return !array_diff(range(11, 12), $this->getSteps());
    }

    public function createExercice(\DateTime $dateDebut, \DateTime $dateCloturePremierExercice, $accountingPeriod) {
        $dateDebut->modify('first day of this month');
        $dateCloturePremierExercice->modify('last day of this month');

        //La date de fin d'exercice est la date de clôture du premier exercice
        $dateFin = $dateCloturePremierExercice;

        $lastUpdatedExercice = null;
        //Il faut mettre à jour tous les exercices et les créer s'ils n'existent pas.
        for ($i = 0; $i < $accountingPeriod; $i++) {
            $exercice = null;
            if ($i < $this->getExercices()->count()) {
                $exercice = $this->getExercices()[$i];
            } else {
                $exercice = new Exercice();
                //On ajoute l'exercice au bilan prévisionnel
                $this->addExercice($exercice);
            }

            // Si on ne traite pas le premier exercice
            if ($lastUpdatedExercice != null) {
                //La date de début d'exercice est la date de fin du précédent à laquelle on ajoute un jour
                $cloneDateFin = clone $dateFin;
                $cloneDateFin->modify('first day of this month');
                $dateDebut = $cloneDateFin->add(new \DateInterval('P1M'));
                /*
                La date de fin d'exercice est la date de début du potentiel prochain exercice (date de début de l'exercice traité à laquelle on ajoute un an) à laquelle on soustrait un jour
                (En se contentant d'ajouter un an à l'ancienne date de fin, si cette dernière est le 29 février, on obtient le 1er mars au lieu du 28 février comme attendu)
                 */
                $secondCloneDateFin = clone $dateFin;
                $secondCloneDateFin->modify('first day of this month');
                $dateFin = $secondCloneDateFin->add(new \DateInterval('P1Y'));
                $dateFin->modify('last day of this month');
            }

            //    On modifie les dates de début et de fin d'exercice
            $exercice->setDateDebut($dateDebut)->setDateFin($dateFin);
            $lastUpdatedExercice = $exercice;
        }

        // Supprimer les exercices en trop
        if ($this->getExercices()->count() > $accountingPeriod) {
            $keepElements = $this->getExercices()->slice($accountingPeriod, $this->getExercices()->count() - $accountingPeriod);
            foreach ($keepElements as $element) {
                foreach ($element->getInfoProduct() as $infoProduit) {
                    $element->removeInfoProduct($infoProduit);
                }
                foreach ($element->getInfoCharge() as $infoCharge) {
                    $element->removeInfoCharge($infoCharge);
                }
                $this->removeExercice($element);
            }
        }

        return $this;
    }

    public function checkDatesValidity(\DateTime $dateDebut) {
        //Check Invest Dates
        foreach ($this->getInvestissements() as $investissement) {
            if ($investissement->getDate() < $dateDebut) {
                $investissement->setDate($dateDebut);
            }
        }
        //Check  Staff Dates
        foreach ($this->getStaffs() as $staff) {
            if ($staff->getCreatedAt() < $dateDebut) {
                $staff->setCreatedAt($dateDebut);
            }
        }

        //Check Fundings Dates
        foreach ($this->getFundings() as $funding) {
            if ($funding->getCreatedAt() < $dateDebut) {
                $funding->setCreatedAt($dateDebut);
            }
        }
    }

    public function calculPnl($tauxChargesFiscales, $tauxIS, $smic, $codeCountry) {

        $previousExercice = null;
        foreach ($this->getExercices() as $key => $exercice) {
            $dateDebut = clone $exercice->getDateDebut();
            $dateFin = $exercice->getDateFin();

            $this->calculChargesFixes($exercice);

            $sommeCAExercice = 0;
            $sommeRCAIExercice = 0;
            // Boucle des mensualités
            while ($dateDebut <= $dateFin) {
                // Chiffre d'affaire, coût de revient et marge brute
                $activite = new Activite($exercice, $dateDebut);
                $sommeCaMensuel = $activite->getSommeCaMensuel();
                $sommeCoutDeRevientMensuel = $activite->getSommeCoutDeRevientMensuel();
                $sommeCommissionMensuelle = $activite->getSommeCommissionMensuelle();
                $sommeMargeBruteMensuelle = $activite->getSommeMargeBruteMensuelle();

                // Achats variables
                $coutVariable = new CoutVariable($exercice, $sommeCaMensuel, $sommeCommissionMensuelle);
                $sommeChargeVariableSurChargeExterneMensuelle = $coutVariable->getSommeChargeVariableSurChargeExterneMensuelle();
                $sommeAchatVariableMensuel = $coutVariable->getSommeAchatVariableMensuel();

                // Marge sur coût variable
                $sommeMargeSurCoutVariableMensuelle = $sommeMargeBruteMensuelle + $sommeAchatVariableMensuel;

                // Charge fixe
                $sommeChargeFixeMensuel = $exercice->getChargeFixeMensuel();

                // Charge personnel
                $chargePersonnel = new ChargePersonnel($this->getStaffs(), $dateDebut, $exercice, $smic);
                $sommeMasseSalarialeHorsTNS = $chargePersonnel->getSommeMasseSalarialeHorsTNS();
                $sommeMasseSalarialeTNS = $chargePersonnel->getSommeMasseSalarialeTNS();
                $sommeCiceMensuel = $chargePersonnel->getSommeCiceMensuel();
                $sommeChargePersonnel = $chargePersonnel->getSommeChargePersonnelMensuelle();
                $sommeChargePatronale = $chargePersonnel->getSommeChargePatronaleMensuelle();

                // Impôts et Taxes du mois
                $impotEtTaxe = new ImpotEtTaxe($tauxChargesFiscales, $sommeCaMensuel, $sommeMasseSalarialeHorsTNS + $sommeMasseSalarialeTNS);
                $sommeImpotsEtTaxesMensuel = $impotEtTaxe->getSommeImpotEtTaxeMensuel();

                // EBE
                $sommeEBEMensuel = $sommeMargeSurCoutVariableMensuelle + $sommeChargeFixeMensuel + $sommeChargePersonnel + $sommeChargePatronale + $sommeImpotsEtTaxesMensuel + $sommeCiceMensuel;

                // Amortissement
                $amortissement = new \BPBundle\Pnl\Amortissement($this->getInvestissements(), $dateDebut, $previousExercice, $exercice->getDateDebut(), $codeCountry);
                $sommeAmortissementMensuel = $amortissement->getSommeAmortissementMensuel();

                // Resultat exploitation
                $sommeResultatExploitationMensuel = $sommeEBEMensuel + $sommeAmortissementMensuel;

                // Resultat financier
                $resultatFinancier = new ResultatFinancier($this->getFundings(), $dateDebut);
                $sommeResultatFinancierMensuel = $resultatFinancier->getSommeResultatFinancierMensuel();

                // Subvention non remboursable
                $subventionNonRemboursable = $resultatFinancier->getSubventionNonRemboursable();

                // RCAI
                $sommeRCAIMensuel = $sommeResultatExploitationMensuel + $sommeResultatFinancierMensuel + $subventionNonRemboursable;

                // Somme CA mensuel et somme RCAI mensuel
                $sommeCAExercice += $sommeCaMensuel;
                $sommeRCAIExercice += $sommeRCAIMensuel;

                $bpInfoMensuel = new BpInfoMensuel();
                $dateInfoMensuel = clone $dateDebut;
                $bpInfoMensuel
                    ->setDate($dateInfoMensuel)
                    ->setCAMensuel($sommeCaMensuel)
                    ->setCoutDeRevientMensuel($sommeCoutDeRevientMensuel)
                    ->setMargeBruteMensuelle($sommeMargeBruteMensuelle)
                    ->setCommissionMensuelle($sommeCommissionMensuelle)
                    ->setChargeVariableSurChargeExterneMensuelle($sommeChargeVariableSurChargeExterneMensuelle)
                    ->setAchatVariableMensuel($sommeAchatVariableMensuel)
                    ->setMargeSurCoutVariableMensuelle($sommeMargeSurCoutVariableMensuelle)
                    ->setChargeFixeSurChargeMensuelle($sommeChargeFixeMensuel)
                    ->setChargePersonnelMensuelle($sommeChargePersonnel)
                    ->setChargePatronaleMensuelle($sommeChargePatronale)
                    ->setMasseSalarialeTNSMensuelle($sommeMasseSalarialeTNS)
                    ->setCiceMensuel($sommeCiceMensuel)
                    ->setImpotEtTaxeMensuel($sommeImpotsEtTaxesMensuel)
                    ->setEbe($sommeEBEMensuel)
                    ->setAmortissementMensuel($sommeAmortissementMensuel)
                    ->setResultatExploitationMensuel($sommeResultatExploitationMensuel)
                    ->setResultatFinancierMensuel($sommeResultatFinancierMensuel)
                    ->setSubventionNonRemboursable($subventionNonRemboursable)
                    ->setRcaiMensuel($sommeRCAIMensuel)
                    ->setNumExercice($key + 1);

                $this->addInfoMensuel($bpInfoMensuel);

                $dateDebut->add(new \DateInterval('P1M'));
            }

            $exercice
                ->setCa($sommeCAExercice)
                ->setRcai($sommeRCAIExercice);

            $sommeRcaiNet = $this->getRcaiNet($exercice);

            $chargesTNSExercice = new ChargesTNSExercice($exercice, $this->getStaffs(), $sommeRcaiNet, $this->getInformation());
            $exercice->setChargesTNS($chargesTNSExercice->getSommeChargesTNSExercice());
            $exercice->setChargesTNSHorsRCAI($chargesTNSExercice->getSommeChargesTNSExerciceHorsRCAI());

            $exercice->setRcai($exercice->getRcai() + $chargesTNSExercice->getSommeChargesTNSExercice());

            $sommeRcaiNet = $this->getRcaiNet($exercice);

            if ($sommeRcaiNet < 0) {
                $exercice->setIs(0)->setDeficitReportable($sommeRcaiNet);
            } else {
                $ISExercice = new ISExercice($tauxIS, $sommeRcaiNet, $sommeCAExercice, $this->getInformation()->getIr());
                $exercice->setIs($ISExercice->getSommeIsExercice());
            }

            $previousExercice = $exercice;
        }

        // IS mensuel
        new ChargesTNSMensuelles($this->getExercices(), $this->getInfoMensuel());
        new ISMensuel($this->getExercices(), $this->getInfoMensuel());
    }

    private function getDateDebutActivite() {
        $date = $this->getExercices()->first();

        return $date->getDateDebut();
    }

    private function getDateFinActivite() {
        $date = $this->getExercices()->last();

        return $date->getDateFin();
    }

    private function calculChargesFixes(Exercice $exercice) {
        $dateDebut = clone $exercice->getDateDebut();
        $dateFin = clone $exercice->getDateFin();
        $nbMonth = 0;
        while ($dateDebut < $dateFin) {
            $nbMonth++;
            $dateDebut->add(new \DateInterval('P1M'));
        }

        $sommeCharge = 0;
        foreach ($exercice->getInfoCharge() as $infoCharge) {
            $coutMensuel = $infoCharge->getCout() / $nbMonth;
            $sommeCharge += $coutMensuel;

            $dateDebut = clone $exercice->getDateDebut();
            $dateFin = clone $exercice->getDateFin();
            while ($dateDebut < $dateFin) {
                $infoCharge->addCoutMensuel($dateDebut, $coutMensuel);
                $dateDebut->add(new \DateInterval('P1M'));
            }
        }

        $sommeCharge *= -1;

        $exercice->setChargeFixeMensuel($sommeCharge);
    }

    private function getRcaiNet(Exercice $exercice) {
        $rcaiNet = $exercice->getRcai();
        if ($exercicePrecedent = $this->getExercicePrecedent($exercice)) {
            $rcaiNet += $exercicePrecedent->getDeficitReportable();
        }
        $exercice->setRcaiNet($rcaiNet);

        return $rcaiNet;
    }

    private function getExercicePrecedent(Exercice $exercice) {
        $exercicePrecedent = null;

        $numeroExercice = $this->getExercices()->indexOf($exercice);
        if ($numeroExercice > 0) {
            $exercicePrecedent = $this->getExercices()->get($numeroExercice - 1);
        }

        return $exercicePrecedent;
    }

    public function calculBilan($codeCountry, $infoBfr) {
        $dateDebutActivite = clone $this->getDateDebutActivite();
        $dateFinActivite = clone $this->getDateFinActivite();
        $tva = $this->getInformation()->getTva();
        $tvaSurEncaissement = $this->getInformation()->getTvaSurEncaissement();
        $stockOrganiserParDate = [];
        $clientOrganiserParDate = [];
        $fournisseurOrganiserParDate = [];
        $fournisseurChargesOrganiserParDate = [];
        $autreCreanceOrganiserParDate = [];
        $autreCreanceChargesOrganiserParDate = [];
        $autreDetteOrganiserParDate = [];
        $detteSocialeOrganiserParDate = [];
        $referenceInfoMensuelPnl = 0;
        $tabVolumeMixPrix = [];
        foreach ($this->getExercices() as $key => $exercice) {
            $dateDebut = clone $exercice->getDateDebut();
            $dateFin = $exercice->getDateFin();

            // Calcul Volume, Mix et Prix
            $annee = $dateDebut->format('mY');
            $tabVolumeMixPrix['display'] = true;
            $tabVolumeMixPrix[$annee]['nbVenteTotale'] = 0;
            foreach ($exercice->getInfoProduct() as $product) {
                //This tab can exist only if ALL of InfoProduct has been filled in expert mode. Otherwise it can't be displayed
                if ($product->getNbVente()) {
                    $tabVolumeMixPrix[$annee]['nbVenteTotale'] += $product->getNbVente();
                    $tabVolumeMixPrix[$annee]['produits'][$product->getProduit()->getId()]['nbVente'] = $product->getNbVente();
                    $tabVolumeMixPrix[$annee]['produits'][$product->getProduit()->getId()]['prix'] = $product->getPrixVente();
                } else {
                    $tabVolumeMixPrix['display'] = false;
                }
            }
            // Fin Calcul Volume, Mix et Prix

            $dispatcherImmobilisation = new DispatcherImmobilisation($this->getInvestissements(), $dateDebut);

            // Initialisation des immobilisations par poste
            $immobilisationIncorporelle = $dispatcherImmobilisation->getImmobilisationIncorporelle();
            $immobilisationCorporelle = $dispatcherImmobilisation->getImmobilisationCorporelle();
            $immobilisationFinanciere = $dispatcherImmobilisation->getImmobilisationFinanciere();

            // Objet permettant de gérer les stocks
            $dispatchBfr = new DispatchBfr($exercice->getInfoProduct(), $exercice->getInfoCharge(), $infoBfr, $dateDebutActivite, $dateFinActivite, $tva, $tvaSurEncaissement);
            // Boucle des mensualités
            while ($dateDebut <= $dateFin) {
                $dateEnCours = clone $dateDebut;

                // Immobilisation
                $actifImmobilises = new ActifImmobilise();

                // Immobilisation incorporelle
                $categorieImmobilisation = new CategorieImmobilisation($immobilisationIncorporelle, $dateEnCours, $codeCountry);
                $actifImmobilises->setImmobilisationIncorporelle($categorieImmobilisation);

                // Immobilisation corporelle
                $categorieImmobilisation = new CategorieImmobilisation($immobilisationCorporelle, $dateEnCours, $codeCountry);
                $actifImmobilises->setImmobilisationCorporelle($categorieImmobilisation);

                // Immobilisation financiere
                $categorieImmobilisation = new CategorieImmobilisation($immobilisationFinanciere, $dateEnCours, $codeCountry);
                $actifImmobilises->setImmobilisationFinanciere($categorieImmobilisation);

                // Total actif immobilise
                $actifImmobilises->calculTotalActifImmobilise();

                // BFR step 1
                // 1 - Stock
                $stockOrganiserParDate = $dispatchBfr->getValeurBfrElement(Helper::POSTE_STOCK, $stockOrganiserParDate, $dateEnCours, $this->getExercices());
                // 2 - Client
                $clientOrganiserParDate = $dispatchBfr->getValeurBfrElement(Helper::POSTE_CLIENT, $clientOrganiserParDate, $dateEnCours, $this->getExercices());

                // 6 - Fournisseurs
                $fournisseurOrganiserParDate = $dispatchBfr->getValeurBfrElement(Helper::POSTE_FOURNISSEUR, $fournisseurOrganiserParDate, $dateEnCours, $this->getExercices(), $stockOrganiserParDate);
                $fournisseurChargesOrganiserParDate = $dispatchBfr->getValeurBfrElementCharges(Helper::POSTE_FOURNISSEUR, $fournisseurChargesOrganiserParDate, $dateEnCours, $this->getExercices());

                // 4 - Autres créances
                $autreCreanceOrganiserParDate = $dispatchBfr->getValeurBfrElement(Helper::POSTE_AUTRE_CREANCE, $autreCreanceOrganiserParDate, $dateEnCours, $this->getExercices(), $stockOrganiserParDate, null, $fournisseurOrganiserParDate);
                $autreCreanceChargesOrganiserParDate = $dispatchBfr->getValeurBfrElementCharges(Helper::POSTE_AUTRE_CREANCE, $autreCreanceChargesOrganiserParDate, $dateEnCours, $this->getExercices(), $fournisseurChargesOrganiserParDate);

                // 5 - Autres dettes
                $autreDetteOrganiserParDate = $dispatchBfr->getValeurBfrElement(Helper::POSTE_AUTRE_DETTE, $autreDetteOrganiserParDate, $dateEnCours, $this->getExercices(), null, $clientOrganiserParDate);

                // BFR step 1
                // 3 - Fournisseurs
                //                $dispatchFournisseur = new DispatchFournisseur($this->getInfoMensuel(), $infoBfr, $dateFinActivite, $tva);
                //                $fournisseurOrganiserParDate = $dispatchFournisseur->getValeurBfrFournisseur();
                // 7 - Dettes sociales
                // Si le business plan n'est pas français, la dette sociale mensuelle est de 0
                if ($this->getInformation()->getAddress()->getCountry()->getCode() == 'Fr') {
                    $detteSociale = new DispatchDetteSociale($this->getStaffs(), $dateEnCours, $dateFinActivite);
                    $detteSocialeOrganiserParDate = $detteSociale->getValeurBfrElement($detteSocialeOrganiserParDate);
                }

                // IS
                $infoMensuel = $this->getInfoMensuel();
                $isMensuelPnl = 0;
                if (array_key_exists($referenceInfoMensuelPnl, $infoMensuel)) {
                    $isMensuelPnl = $infoMensuel[$referenceInfoMensuelPnl]->getImpotSurSociete();
                }
                $exercice_N_Moins_1 = $this->getExercicePrecedent($exercice);
                $exercice_N_Moins_2 = null;
                if ($exercice_N_Moins_1) {
                    $exercice_N_Moins_2 = $this->getExercicePrecedent($exercice_N_Moins_1);
                }

                $isMensuel = new IsBilan($exercice, $exercice_N_Moins_1, $exercice_N_Moins_2, $dateEnCours, $isMensuelPnl);

                $chargesTNSMensuellesPnl = 0;
                if (array_key_exists($referenceInfoMensuelPnl, $infoMensuel)) {
                    $chargesTNSMensuellesPnl = $infoMensuel[$referenceInfoMensuelPnl]->getChargesTNSMensuelles();
                }
                $chargesTNSMensuelles = new ChargesTNSBilan($exercice, $exercice_N_Moins_1, $exercice_N_Moins_2, $dateEnCours, $chargesTNSMensuellesPnl);
                // Dette fournisseur immobilisation
                $valeur = 0;
                $valeurDetteFournisseurImmo = $this->calculDetteFournisseurImmo($dateEnCours, $infoBfr->getProvider(), $tva, $valeur);

                //CA
                $CAMensuelPnl = 0;
                $achatMensuelPnl = 0;
                $commissionPnl = 0;
                $achatVariablePnl = 0;
                $chargeVariableSurChargeExternePnl = 0;
                if (array_key_exists($referenceInfoMensuelPnl, $infoMensuel)) {
                    $CAMensuelPnl = $infoMensuel[$referenceInfoMensuelPnl]->getCaMensuel();
                    $achatMensuelPnl = $infoMensuel[$referenceInfoMensuelPnl]->getCoutDeRevientMensuel();
                    $commissionPnl = $infoMensuel[$referenceInfoMensuelPnl]->getCommissionMensuelle();
                    $achatVariablePnl = $infoMensuel[$referenceInfoMensuelPnl]->getAchatVariableMensuel();
                    $chargeVariableSurChargeExternePnl = $infoMensuel[$referenceInfoMensuelPnl]->getChargeVariableSurChargeExterne();
                    $chargePersonnel = $infoMensuel[$referenceInfoMensuelPnl]->getChargePersonnelMensuelle();
                    $cice = $infoMensuel[$referenceInfoMensuelPnl]->getCiceMensuel();
                    $chargePatronale = $infoMensuel[$referenceInfoMensuelPnl]->getChargePatronaleMensuelle();
                    $impotsEtTaxes = $infoMensuel[$referenceInfoMensuelPnl]->getImpotEtTaxeMensuel();
                }
                // Trésorie
                /*
                1 - Emprunt bancaire
                2 - Compte courant associé
                3 - Subvention remboursable
                 */
                $tresorerie = new Tresorerie($dateEnCours);
                $tresorerie->init($this->getFundings());

                // Capitaux propre
                /*
                1 - Capital social
                 */
                $capitalSocial = new CapitalSocial($dateEnCours, $dateDebutActivite, $this->getInformation()->getCapital());
                $capitalSocial->init($this->getFundings());

                // Valeur souscription emprunt
                $valeurSouscriptionEmpruntADate = 0;
                foreach ($this->getFundings() as $funding) {
                    if ($funding->getChargeLabel()->getIsEmpruntBancaire()) {
                        $dateClone = clone $funding->getCreatedAt();
                        $dateClone->modify('first day of this month');
                        if ($dateClone == $dateDebut) {
                            $valeurSouscriptionEmpruntADate += $funding->getAmount();
                        }
                    }
                }

                $infoBilanMensuel = new InfoBilanMensuel();
                $infoBilanMensuel
                    ->setDate($dateEnCours)
                    ->setActifsImmobilises($actifImmobilises)
                    ->setIs($isMensuel)
                    ->setChargesTNS($chargesTNSMensuelles)
                    ->setCA($CAMensuelPnl)
                    ->setDetteFournisseurImmo($valeurDetteFournisseurImmo)
                    ->setTresorerie($tresorerie)
                    ->setCapitalSocial($capitalSocial)
                    ->setValeurSouscriptionEmprunt($valeurSouscriptionEmpruntADate)
                    ->setAchat($achatMensuelPnl)
                    ->setCommission($commissionPnl)
                    ->setAchatVariable($achatVariablePnl)
                    ->setChargePersonnel($chargePersonnel)
                    ->setCice($cice)
                    ->setChargePatronale($chargePatronale)
                    ->setImpotsEtTaxes($impotsEtTaxes)
                    ->setChargeVariableSurChargeExterne($chargeVariableSurChargeExternePnl)
                    ->setNumExercice($key + 1);

                $this->addInfoBilanMensuel($infoBilanMensuel);
                $dateDebut->add(new \DateInterval('P1M'));

                // Clé faisant référence au tableau infoMensuel
                $referenceInfoMensuelPnl++;
            }
        }

        // Calcul mix par produit
        if ($tabVolumeMixPrix['display']) {
            foreach ($tabVolumeMixPrix as $k => $t) {
                if ($k !== 'display' && array_key_exists('produits', $t)) {
                    foreach ($t['produits'] as $l => $prod) {
                        if ($t['nbVenteTotale'] != 0) {
                            $tabVolumeMixPrix[$k]['produits'][$l]['mix'] = $prod['nbVente'] / $t['nbVenteTotale'];
                        } else {
                            $tabVolumeMixPrix[$k]['produits'][$l]['mix'] = 0;
                        }
                    }
                }
            }

            $tabVMPFinal = [];
            $lastPeriode = null;
            foreach ($tabVolumeMixPrix as $k => $t) {
                if ($k !== 'display' && array_key_exists('produits', $t)) {
                    if ($k == $dateDebutActivite->format('mY')) {
                        $lastPeriode = $k;
                        continue;
                    }
                    foreach ($t['produits'] as $l => $produit) {
                        $tabVMPFinal[$k]['produits'][$l]['effetVolume'] = ($t['nbVenteTotale'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['mix'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['prix']) - ($tabVolumeMixPrix[$lastPeriode]['nbVenteTotale'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['mix'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['prix']);
                        $tabVMPFinal[$k]['produits'][$l]['effetMix'] = ($t['nbVenteTotale'] * $produit['mix'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['prix']) - ($t['nbVenteTotale'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['mix'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['prix']);
                        $tabVMPFinal[$k]['produits'][$l]['effetPrix'] = ($t['nbVenteTotale'] * $produit['mix'] * $produit['prix']) - ($t['nbVenteTotale'] * $produit['mix'] * $tabVolumeMixPrix[$lastPeriode]['produits'][$l]['prix']);
                    }
                    $lastPeriode = $k;
                }
            }

            $this->setEffetVolumeMixPrix($tabVMPFinal);
        }

        // 6 - Dettes fiscales
        $dispatchDetteFiscale = new DispatchDetteFiscale($this->getInfoMensuel());
        $detteFiscaleOrganiserParDate = $dispatchDetteFiscale->getValeurBfrDetteFiscale();

        // START : Le code ci-dessous permet de dispatcher les tableaux stock, client, etc. dans les bons objets $infoBilanMensuel
        // BFR final step - 1
        foreach ($this->getInfoBilanMensuel() as $infoBilanMensuel) {
            $bfrExploitation = new BfrExploitation();
            $dateInfoBilanMensuel = clone $infoBilanMensuel->getDate();
            $bfrExploitation->setDate($dateInfoBilanMensuel);
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $stockOrganiserParDate)) {
                $bfrExploitation->setStock($stockOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($stockOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $clientOrganiserParDate)) {
                $bfrExploitation->setClient($clientOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($clientOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreCreanceOrganiserParDate)) {
                $bfrExploitation->setAutreCreance($autreCreanceOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($autreCreanceOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreCreanceChargesOrganiserParDate)) {
                $bfrExploitation->setAutreCreanceChargesExternes($autreCreanceChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($autreCreanceChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $fournisseurChargesOrganiserParDate)) {
                $bfrExploitation->setFournisseurChargesExternes($fournisseurChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($fournisseurChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $fournisseurOrganiserParDate)) {
                $bfrExploitation->setFournisseur($fournisseurOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($fournisseurOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $detteSocialeOrganiserParDate)) {
                $detteSociale = $detteSocialeOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')];
                $newDetteSociale = new DetteSociale();
                $sommeDetteSociale = $infoBilanMensuel->getChargesTNS()->getDetteFinale();
                foreach ($detteSociale as $dette) {
                    $detailDetteSociale = new DetailDetteSociale($dette['staff'], $dette['urssaf'], $dette['retraite']);
                    $newDetteSociale->addDetailDetteSociale($detailDetteSociale);
                    $sommeDetteSociale += $detailDetteSociale->getSommeValeur();
                }
                $newDetteSociale->setSommeValeur($sommeDetteSociale);
                $bfrExploitation->setDetteSociale($newDetteSociale);
                unset($detteSocialeOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $detteFiscaleOrganiserParDate)) {
                $bfrExploitation->setDetteFiscale($detteFiscaleOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($detteFiscaleOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreDetteOrganiserParDate)) {
                $bfrExploitation->setAutreDette($autreDetteOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                unset($autreDetteOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            $infoBilanMensuel->setBfr($bfrExploitation);
        }

        // BFR final step - 2 : dates restantes (dates qui dépassent la date de fin du dernier exercice)
        $nbExercice = $this->getExercices()->count();
        $datesRestantes = true;
        while ($datesRestantes) {
            $dateInfoBilanMensuel->add(new \DateInterval('P1M'));
            $bfrExploitation = new BfrExploitation();
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $stockOrganiserParDate)) {
                $bfrExploitation->setStock($stockOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($stockOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $clientOrganiserParDate)) {
                $bfrExploitation->setClient($clientOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($clientOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreCreanceOrganiserParDate)) {
                $bfrExploitation->setAutreCreance($autreCreanceOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($autreCreanceOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreCreanceChargesOrganiserParDate)) {
                $bfrExploitation->setAutreCreanceChargesExternes($autreCreanceChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($autreCreanceChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $fournisseurOrganiserParDate)) {
                $bfrExploitation->setFournisseur($fournisseurOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($fournisseurOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $fournisseurChargesOrganiserParDate)) {
                $bfrExploitation->setFournisseurChargesExternes($fournisseurChargesOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($fournisseurOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $detteSocialeOrganiserParDate)) {
                $detteSociale = $detteSocialeOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')];
                $newDetteSociale = new DetteSociale();
                $sommeDetteSociale = $infoBilanMensuel->getChargesTNS()->getDetteFinale();
                foreach ($detteSociale as $dette) {
                    $detailDetteSociale = new DetailDetteSociale($dette['staff'], $dette['urssaf'], $dette['retraite']);
                    $newDetteSociale->addDetailDetteSociale($detailDetteSociale);
                    $sommeDetteSociale += $detailDetteSociale->getSommeValeur();
                }
                $newDetteSociale->setSommeValeur($sommeDetteSociale);
                $bfrExploitation->setDetteSociale($newDetteSociale);
                unset($detteSocialeOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $detteFiscaleOrganiserParDate)) {
                $bfrExploitation->setDetteFiscale($detteFiscaleOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($detteFiscaleOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }
            if (array_key_exists($dateInfoBilanMensuel->format('Y-m-d'), $autreDetteOrganiserParDate)) {
                $bfrExploitation->setAutreDette($autreDetteOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
                $bfrExploitation->setOutOfExercice(true);
                unset($autreDetteOrganiserParDate[$dateInfoBilanMensuel->format('Y-m-d')]);
            }

            if ($bfrExploitation->isOutOfExercice()) {
                $infoBilanMensuelOut = new InfoBilanMensuel();
                $infoBilanMensuelOut->setNumExercice($nbExercice + 1)
                    ->setOutOfExercice(true);
                $infoBilanMensuelOut->setBfr($bfrExploitation);
                $this->addInfoBilanMensuel($infoBilanMensuelOut);
            } else {
                $datesRestantes = false;
            }
        }
        // END: Le code ci-dessus a permis de dispatcher les tableaux stock, client, etc. dans les bons objets $infoBilanMensuel
        // Capitaux propre
        /*
        2 - Réserves
         */
        $reserve = new Reserve();
        $reserve->init($this->getExercices(), $this->getFundings());

        // Tableau de flux de trésorerie
        $builderFluxTresorerie = new BuilderFluxTresorerie();
        $builderFluxTresorerie->init(
            $this->getInfoMensuel(), $this->getInfoBilanMensuel(), $this->getExercices(), $this->getInvestissements(), $this->getFundings(), $codeCountry
        );

        $tableauDeFluxDeTresorerie = $builderFluxTresorerie->getTableauDeFluxDeTresorerie();

        // Dispatch Reserve, tableauDeFluxDeTresorerie dans le bilan et tresorerie nette
        $tableauDeReserve = $reserve->getTableauReserve();
        foreach ($this->getInfoBilanMensuel() as $bilanMensuel) {
            foreach ($tableauDeFluxDeTresorerie as $key => $fluxTresorerie) {
                if ($bilanMensuel->getDate() == $fluxTresorerie->getDate()) {
                    $bilanMensuel->getTresorerie()->setFluxTresorerie($fluxTresorerie);
                    unset($tableauDeFluxDeTresorerie[$key]);
                }
            }

            $tresorerieNet = $bilanMensuel->getTresorerie()->getSommeEmpruntBancaire();
            $tresorerieNet += $bilanMensuel->getTresorerie()->getSoldeCompteCourantAssocie();
            $tresorerieNet += $bilanMensuel->getTresorerie()->getSommeSubventionRemboursable();
            if ($bilanMensuel->getTresorerie()) {
                $tresorerieNet += $bilanMensuel->getTresorerie()->getFluxTresorerie()->getTresorerieCloture();
            }

            $bilanMensuel->getTresorerie()->setTresorerieNette($tresorerieNet);

            if (array_key_exists($bilanMensuel->getDate()->format('Y-m-d'), $tableauDeReserve)) {
                $bilanMensuel->getCapitalSocial()->setSommeReserve($tableauDeReserve[$bilanMensuel->getDate()->format('Y-m-d')]);
                unset($tableauDeReserve[$bilanMensuel->getDate()->format('Y-m-d')]);
            }
        }

        // Capitaux propre
        /*
        3 - Resultat exercice
         */
        $infoBilanMensuel = $this->getInfoBilanMensuel();
        $sommeResultatExercice = 0;
        $numExercice = 1;
        foreach ($this->getInfoMensuel() as $key => $infoMensuel) {
            if ($infoMensuel->getNumExercice() > $numExercice) {
                $sommeResultatExercice = 0;
                $numExercice++;
            }
            if (array_key_exists($key, $infoBilanMensuel)) {
                $sommeResultatExercice += $infoMensuel->getResultatNetMensuel();
                $infoBilanMensuel[$key]->getCapitalSocial()->setSommeResultatExercice($sommeResultatExercice);
            }
        }

        // Actif net et capitaux propres
        foreach ($this->getInfoBilanMensuel() as $infoBilanMensuel) {
            // Actif net
            $infoBilanMensuel->setActifNet(
                $infoBilanMensuel->getActifsImmobilises()->getTotalActifImmobilise() +
                $infoBilanMensuel->getBfr()->getSommeValeur() +
                ($infoBilanMensuel->getIs() ? $infoBilanMensuel->getIs()->getDetteFinale() : 0) +
                $infoBilanMensuel->getDetteFournisseurImmo() +
                $infoBilanMensuel->getTresorerie()->getTresorerieNette()
            );

            // Capitaux propres
            $infoBilanMensuel->setCapitauxPropres(
                $infoBilanMensuel->getCapitalSocial()->getSommeCapitalSocial() +
                $infoBilanMensuel->getCapitalSocial()->getSommeReserve() +
                $infoBilanMensuel->getCapitalSocial()->getSommeResultatExercice()
            );
        }
    }

    private function calculDetteFournisseurImmo($dateEnCours, $delai, $tva, $valeur) {
        // TODO: mis en commentaire momentanément
        return 0;
        $valeurDuMois = 0;
        foreach ($this->getInvestissements() as $investissement) {
            $dateInvestissement = clone $investissement->getDate();
            $dateInvestissement->modify('first day of this month');
            if ($dateInvestissement == $dateEnCours) {
                $valeurDuMois += $investissement->getAmount();
            }
        }

        if ($valeurDuMois != 0) {
            //dump($tva);
            //dump($valeurDuMois);
            //dump($valeurDuMois * (1 + ($tva / 100)));
            $currentDate = clone $dateEnCours;
            $nbJourDuMois = Helper::nbDayInMonth($currentDate);
            $nbJourRestant = $delai - $nbJourDuMois;
            if ($nbJourRestant > 0) {
                $valeur += $valeurDuMois;
                $currentDate->sub(new \DateInterval('P1M'));
                return $this->calculDetteFournisseurImmo($currentDate, $nbJourRestant, $tva, $valeur);
            } else {
                $valeur += ($delai / $nbJourDuMois) * ($valeurDuMois);
            }
        }

        return $valeur;
    }

    public function calculPnlParAn() {
        $allInfoMensuel = $this->getInfoMensuel();
        $dateDebutActivite = $this->getDateDebutActivite();
        $dateFinPremierExercice = $this->getInformation()->getClosingDate();
        $interval = Helper::nbMonthDiff($dateDebutActivite, $dateFinPremierExercice) + 1;

        $infoMensuelParAn = [];
        foreach ($allInfoMensuel as $infoMensuel) {
            $exercice = $infoMensuel->getNumExercice();
            if (empty($infoMensuelParAn[$exercice])) {
                $infoMensuelParAn[$exercice] = [];
            }
            $infoMensuelParAn[$exercice]['date'] = $infoMensuel->getDate()->format('Y');
            $infoMensuelParAn[$exercice]['nbMonth'] = ($exercice === 1 ? $interval : 12);
            $infoMensuelParAn[$exercice]['ca'] = (!empty($infoMensuelParAn[$exercice]['ca']) ? $infoMensuelParAn[$exercice]['ca'] + $infoMensuel->getCaMensuel() : $infoMensuel->getCaMensuel());
            $infoMensuelParAn[$exercice]['achat'] = (!empty($infoMensuelParAn[$exercice]['achat']) ? $infoMensuelParAn[$exercice]['achat'] + $infoMensuel->getCoutDeRevientMensuel() : $infoMensuel->getCoutDeRevientMensuel());
            $infoMensuelParAn[$exercice]['margeBrute'] = (!empty($infoMensuelParAn[$exercice]['margeBrute']) ? $infoMensuelParAn[$exercice]['margeBrute'] + $infoMensuel->getMargeBruteMensuelle() : $infoMensuel->getMargeBruteMensuelle());
            $infoMensuelParAn[$exercice]['commission'] = (!empty($infoMensuelParAn[$exercice]['commission']) ? $infoMensuelParAn[$exercice]['commission'] + $infoMensuel->getCommissionMensuelle() : $infoMensuel->getCommissionMensuelle());
            $infoMensuelParAn[$exercice]['chargeVariableSurChargeExterne'] = (!empty($infoMensuelParAn[$exercice]['chargeVariableSurChargeExterne']) ? $infoMensuelParAn[$exercice]['chargeVariableSurChargeExterne'] + $infoMensuel->getChargeVariableSurChargeExterne() : $infoMensuel->getChargeVariableSurChargeExterne());
            $infoMensuelParAn[$exercice]['achatVariable'] = (!empty($infoMensuelParAn[$exercice]['achatVariable']) ? $infoMensuelParAn[$exercice]['achatVariable'] + $infoMensuel->getAchatVariableMensuel() : $infoMensuel->getAchatVariableMensuel());
            $infoMensuelParAn[$exercice]['margeSurCoutVariable'] = (!empty($infoMensuelParAn[$exercice]['margeSurCoutVariable']) ? $infoMensuelParAn[$exercice]['margeSurCoutVariable'] + $infoMensuel->getMargeSurCoutVariableMensuelle() : $infoMensuel->getMargeSurCoutVariableMensuelle());
            $infoMensuelParAn[$exercice]['chargeFixe'] = (!empty($infoMensuelParAn[$exercice]['chargeFixe']) ? $infoMensuelParAn[$exercice]['chargeFixe'] + $infoMensuel->getChargeFixeSurChargeMensuelle() : $infoMensuel->getChargeFixeSurChargeMensuelle());
            $infoMensuelParAn[$exercice]['chargePersonnel'] = (!empty($infoMensuelParAn[$exercice]['chargePersonnel']) ? $infoMensuelParAn[$exercice]['chargePersonnel'] + $infoMensuel->getChargePersonnelMensuelle() : $infoMensuel->getChargePersonnelMensuelle());
            $infoMensuelParAn[$exercice]['chargePatronale'] = (!empty($infoMensuelParAn[$exercice]['chargePatronale']) ? $infoMensuelParAn[$exercice]['chargePatronale'] + $infoMensuel->getChargePatronaleMensuelle() : $infoMensuel->getChargePatronaleMensuelle());
            $infoMensuelParAn[$exercice]['chargeSocialeExploit'] = (!empty($infoMensuelParAn[$exercice]['chargeSocialeExploit']) ? $infoMensuelParAn[$exercice]['chargeSocialeExploit'] + $infoMensuel->getChargeSocialeExploitationMensuelle() : $infoMensuel->getChargeSocialeExploitationMensuelle());
            $infoMensuelParAn[$exercice]['cice'] = (!empty($infoMensuelParAn[$exercice]['cice']) ? $infoMensuelParAn[$exercice]['cice'] + $infoMensuel->getCiceMensuel() : $infoMensuel->getCiceMensuel());
            $infoMensuelParAn[$exercice]['impotEtTaxe'] = (!empty($infoMensuelParAn[$exercice]['impotEtTaxe']) ? $infoMensuelParAn[$exercice]['impotEtTaxe'] + $infoMensuel->getImpotEtTaxeMensuel() : $infoMensuel->getImpotEtTaxeMensuel());
            $infoMensuelParAn[$exercice]['ebe'] = (!empty($infoMensuelParAn[$exercice]['ebe']) ? $infoMensuelParAn[$exercice]['ebe'] + $infoMensuel->getEbe() : $infoMensuel->getEbe());
            $infoMensuelParAn[$exercice]['amortissement'] = (!empty($infoMensuelParAn[$exercice]['amortissement']) ? $infoMensuelParAn[$exercice]['amortissement'] + $infoMensuel->getAmortissementMensuel() : $infoMensuel->getAmortissementMensuel());
            $infoMensuelParAn[$exercice]['resultatExploitation'] = (!empty($infoMensuelParAn[$exercice]['resultatExploitation']) ? $infoMensuelParAn[$exercice]['resultatExploitation'] + $infoMensuel->getResultatExploitationMensuel() : $infoMensuel->getResultatExploitationMensuel());
            $infoMensuelParAn[$exercice]['resultatFinancier'] = (!empty($infoMensuelParAn[$exercice]['resultatFinancier']) ? $infoMensuelParAn[$exercice]['resultatFinancier'] + $infoMensuel->getResultatFinancierMensuel() : $infoMensuel->getResultatFinancierMensuel());
            $infoMensuelParAn[$exercice]['subventionNonRemboursable'] = (!empty($infoMensuelParAn[$exercice]['subventionNonRemboursable']) ? $infoMensuelParAn[$exercice]['subventionNonRemboursable'] + $infoMensuel->getSubventionNonRemboursable() : $infoMensuel->getSubventionNonRemboursable());
            $infoMensuelParAn[$exercice]['rcai'] = (!empty($infoMensuelParAn[$exercice]['rcai']) ? $infoMensuelParAn[$exercice]['rcai'] + $infoMensuel->getRcaiMensuel() : $infoMensuel->getRcaiMensuel());
            $infoMensuelParAn[$exercice]['impotSurSociete'] = (!empty($infoMensuelParAn[$exercice]['impotSurSociete']) ? $infoMensuelParAn[$exercice]['impotSurSociete'] + $infoMensuel->getImpotSurSociete() : $infoMensuel->getImpotSurSociete());
            $infoMensuelParAn[$exercice]['chargesTNS'] = (!empty($infoMensuelParAn[$exercice]['chargesTNS']) ? $infoMensuelParAn[$exercice]['chargesTNS'] + $infoMensuel->getChargesTNSMensuelles() : $infoMensuel->getChargesTNSMensuelles());
            $infoMensuelParAn[$exercice]['resultatNet'] = (!empty($infoMensuelParAn[$exercice]['resultatNet']) ? $infoMensuelParAn[$exercice]['resultatNet'] + $infoMensuel->getResultatNetMensuel() : $infoMensuel->getResultatNetMensuel());
            $infoMensuelParAn[$exercice]['valeurAjoutee'] = (!empty($infoMensuelParAn[$exercice]['valeurAjoutee']) ? $infoMensuelParAn[$exercice]['valeurAjoutee'] + ($infoMensuel->getMargeSurCoutVariableMensuelle() + $infoMensuel->getChargeFixeSurChargeMensuelle()) : $infoMensuel->getMargeSurCoutVariableMensuelle() + $infoMensuel->getChargeFixeSurChargeMensuelle());
        }

        $this->setInfoAnnuel($infoMensuelParAn);
    }

    public function calculBilanParAn() {
        $allInfoBilanMensuel = $this->getInfoBilanMensuel();
        $dateDebutActivite = $this->getDateDebutActivite();
        $dateFinPremierExercice = $this->getInformation()->getClosingDate();
        $interval = Helper::nbMonthDiff($dateDebutActivite, $dateFinPremierExercice) + 1;

        $infoMensuelParAn = [];
        foreach ($allInfoBilanMensuel as $infoMensuel) {
            $exercice = $infoMensuel->getNumExercice();
            $actifsImmobilises = $infoMensuel->getActifsImmobilises();

            $bfr = $infoMensuel->getBfr();
            $stock = ($bfr->getStock() ? $bfr->getStock()->getSommeValeur() : 0);
            $client = ($bfr->getClient() ? $bfr->getClient()->getSommeValeur() : 0);
            $autreCreance = ($bfr->getAutreCreance() ? $bfr->getAutreCreance()->getSommeValeur() : 0);
            $autreCreance += ($bfr->getAutreCreanceChargesExternes() ? $bfr->getAutreCreanceChargesExternes()->getSommeValeur() : 0);
            $fournisseur = ($bfr->getFournisseur() ? $bfr->getFournisseur()->getSommeValeur() : 0);
            $fournisseur += ($bfr->getFournisseurChargesExternes() ? $bfr->getFournisseurChargesExternes()->getSommeValeur() : 0);
            $detteFiscale = ($bfr->getDetteFiscale() ? $bfr->getDetteFiscale() : 0);
            $detteSociale = ($bfr->getDetteSociale() ? $bfr->getDetteSociale()->getSommeValeur() : 0);
            $autreDette = ($bfr->getAutreDette() ? $bfr->getAutreDette()->getSommeValeur() : 0);
            $is = ($infoMensuel->getIs() ? $infoMensuel->getIs()->getDetteFinale() : 0);

            $tresorerie = $infoMensuel->getTresorerie();

            $capitalSocial = $infoMensuel->getCapitalSocial();

            $infoMensuelParAn[$exercice]['date'] = $infoMensuel->getDate()->format('Y');
            $infoMensuelParAn[$exercice]['nbMonth'] = ($exercice === 1 ? $interval : 12);
            $infoMensuelParAn[$exercice]['immoCorporel'] = $actifsImmobilises->getImmobilisationCorporelle()->getMontantTotal();
            $infoMensuelParAn[$exercice]['immoIncorporelle'] = $actifsImmobilises->getImmobilisationInCorporelle()->getMontantTotal();
            $infoMensuelParAn[$exercice]['immoFinanciere'] = $actifsImmobilises->getImmobilisationFinanciere()->getMontantTotal();
            $infoMensuelParAn[$exercice]['actifsImmo'] = $actifsImmobilises->getTotalActifImmobilise();
            $infoMensuelParAn[$exercice]['stocks'] = $stock;
            $infoMensuelParAn[$exercice]['client'] = $client;
            $infoMensuelParAn[$exercice]['autreCreance'] = $autreCreance;
            $infoMensuelParAn[$exercice]['fournisseur'] = $fournisseur;
            $infoMensuelParAn[$exercice]['detteFiscale'] = $detteFiscale;
            $infoMensuelParAn[$exercice]['detteSociale'] = $detteSociale;
            $infoMensuelParAn[$exercice]['autreDette'] = $autreDette;
            $infoMensuelParAn[$exercice]['bfrExploitation'] = $bfr->getSommeValeur();
            $infoMensuelParAn[$exercice]['is'] = $is;
            $infoMensuelParAn[$exercice]['detteFournisseurImmo'] = $infoMensuel->getDetteFournisseurImmo();
            $infoMensuelParAn[$exercice]['empruntBancaire'] = $tresorerie->getSommeEmpruntBancaire();
            $infoMensuelParAn[$exercice]['compteCourantAssocie'] = $tresorerie->getSoldeCompteCourantAssocie();
            $infoMensuelParAn[$exercice]['subventionRemboursable'] = $tresorerie->getSommeSubventionRemboursable();
            $infoMensuelParAn[$exercice]['tresorerie'] = $tresorerie->getTresorerieNette();
            $infoMensuelParAn[$exercice]['capitalSocial'] = $capitalSocial->getSommeCapitalSocial();
            $infoMensuelParAn[$exercice]['reserve'] = $capitalSocial->getSommeReserve();
            $infoMensuelParAn[$exercice]['resultatExercice'] = $capitalSocial->getSommeResultatExercice();
            $infoMensuelParAn[$exercice]['actifNet'] = $infoMensuel->getActifNet();
            $infoMensuelParAn[$exercice]['capitauxPropres'] = $infoMensuel->getCapitauxPropres();
            $infoMensuelParAn[$exercice]['totalBilan'] = $actifsImmobilises->getTotalActifImmobilise() + $stock + $client + $autreCreance + ($tresorerie->getSoldeCompteCourantAssocie() > 0 ? $tresorerie->getSoldeCompteCourantAssocie() : 0) + ($tresorerie->getTresorerieNette() > 0 ? $tresorerie->getTresorerieNette() : 0);
            $infoMensuelParAn[$exercice]['actifCirculant'] = $stock + $client + $autreCreance;
            $infoMensuelParAn[$exercice]['passifCirculant'] = $fournisseur + $detteFiscale + $detteSociale + $autreDette;

            $fluxTresorerie = $tresorerie->getFluxTresorerie();
            if ($fluxTresorerie) {
                $infoMensuelParAn[$exercice]['fluxtresorerie']['ca'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['ca']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['ca'] + $fluxTresorerie->getCA() : $fluxTresorerie->getCA());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['achatsHorsStock'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['achatsHorsStock']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['achatsHorsStock'] + $fluxTresorerie->getAchatsHorsStock() : $fluxTresorerie->getAchatsHorsStock());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['achatStock'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['achatStock']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['achatStock'] + $fluxTresorerie->getAchatStock() : $fluxTresorerie->getAchatStock());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['personnel'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['personnel']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['personnel'] + $fluxTresorerie->getPersonnel() : $fluxTresorerie->getPersonnel());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['impotsEtTaxes'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['impotsEtTaxes']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['impotsEtTaxes'] + $fluxTresorerie->getImpotsEtTaxes() : $fluxTresorerie->getImpotsEtTaxes());

                $infoMensuelParAn[$exercice]['fluxtresorerie']['ebe'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['ebe']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['ebe'] + $fluxTresorerie->getEbe() : $fluxTresorerie->getEbe());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['variationBfr'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['variationBfr']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['variationBfr'] + $fluxTresorerie->getVariationBfr() : $fluxTresorerie->getVariationBfr());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxExploitation'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['fluxExploitation']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxExploitation'] + $fluxTresorerie->getFluxExploitation() : $fluxTresorerie->getFluxExploitation());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['resultatFinancier'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['resultatFinancier']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['resultatFinancier'] + $fluxTresorerie->getResultatFinancier() : $fluxTresorerie->getResultatFinancier());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['subventionNonRemboursable'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['subventionNonRemboursable']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['subventionNonRemboursable'] + $fluxTresorerie->getSubventionNonRemboursable() : $fluxTresorerie->getSubventionNonRemboursable());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['decaissement'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['decaissement']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['decaissement'] + $fluxTresorerie->getDecaissement() : $fluxTresorerie->getDecaissement());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxActivite'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['fluxActivite']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxActivite'] + $fluxTresorerie->getFluxactivite() : $fluxTresorerie->getFluxactivite());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['investissement'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['investissement']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['investissement'] + $fluxTresorerie->getInvestissement() : $fluxTresorerie->getInvestissement());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['variationDetteFournisseurImmo'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['variationDetteFournisseurImmo']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['variationDetteFournisseurImmo'] + $fluxTresorerie->getVariationDetteFournisseurImmo() : $fluxTresorerie->getVariationDetteFournisseurImmo());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['freeCashFlow'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['freeCashFlow']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['freeCashFlow'] + $fluxTresorerie->getFreeCashFlow() : $fluxTresorerie->getFreeCashFlow());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['augmentationCapitale'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['augmentationCapitale']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['augmentationCapitale'] + $fluxTresorerie->getAugmentationCapital() : $fluxTresorerie->getAugmentationCapital());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['variationCompteCourant'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['variationCompteCourant']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['variationCompteCourant'] + $fluxTresorerie->getVariationCompteCourant() : $fluxTresorerie->getVariationCompteCourant());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['variationEmprunt'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['variationEmprunt']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['variationEmprunt'] + $fluxTresorerie->getVariationEmprunt() : $fluxTresorerie->getVariationEmprunt());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['subventionRemboursable'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['subventionRemboursable']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['subventionRemboursable'] + $fluxTresorerie->getSubventionRemboursable() : $fluxTresorerie->getSubventionRemboursable());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['dividende'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['dividende']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['dividende'] + $fluxTresorerie->getDividende() : $fluxTresorerie->getDividende());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxFinancement'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['fluxFinancement']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxFinancement'] + $fluxTresorerie->getFluxFinancement() : $fluxTresorerie->getFluxFinancement());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxTresorerie'] = (!empty($infoMensuelParAn[$exercice]['fluxtresorerie']['fluxTresorerie']) ? $infoMensuelParAn[$exercice]['fluxtresorerie']['fluxTresorerie'] + $fluxTresorerie->getFluxDeTresorerie() : $fluxTresorerie->getFluxDeTresorerie());
                $infoMensuelParAn[$exercice]['fluxtresorerie']['tresorerieOuverture'] = ($exercice > 1 ? $infoMensuelParAn[($exercice - 1)]['fluxtresorerie']['tresorerieCloture'] : 0);
                $infoMensuelParAn[$exercice]['fluxtresorerie']['tresorerieCloture'] = $fluxTresorerie->getTresorerieCloture();

                $annuite = $fluxTresorerie->getVariationEmprunt() - $infoMensuel->getValeurSouscriptionEmprunt();
                $infoMensuelParAn[$exercice]['annuite'] = (!empty($infoMensuelParAn[$exercice]['annuite']) ? $infoMensuelParAn[$exercice]['annuite'] + $annuite : $annuite);
            }
        }

        $this->setInfoBilanAnnuel($infoMensuelParAn);
    }

    /**
     * Set service
     *
     * @param \BPBundle\Entity\Service $service
     *
     * @return BusinessPlan
     */
    public function setService(\BPBundle\Entity\Service $service) {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \BPBundle\Entity\Service
     */
    public function getService() {
        return $this->service;
    }

    public function getTotalIncome() {
        $totalIncome = [
        ];

        foreach ($this->getExercices() as $exercice) {
            $totalIncome[] = $this->getTotalIncomeExercice($exercice);
        }

        return $totalIncome;
    }

    private function getTotalIncomeExercice($exercice) {
        $totalCA = 0;
        $totalMarge = 0;
        foreach ($exercice->getInfoProduct() as $infoProduct) {
            $totalCA += $infoProduct->getCAExercice();
            $totalMarge += $infoProduct->getCAExercice() * $infoProduct->getMarge() / 100;
        }

        $totalTauxMarge = 0;
        if ($totalCA != 0) {
            $totalTauxMarge += $totalMarge / $totalCA * 100;
        }

        return ['totalCA' => $totalCA, 'totalMarge' => $totalMarge, 'totalTauxMarge' => $totalTauxMarge];
    }

    public function getTotalCharge() {
        $totalCharge = [
        ];

        foreach ($this->getExercices() as $exercice) {
            $totalChargeCout = 0;
            foreach ($exercice->getInfoCharge() as $infoCharge) {
                if ($infoCharge->getCharge()->getTaux()) {
                    $totalChargeCout += $infoCharge->getCharge()->getTaux() * $this->getTotalIncomeExercice($exercice)['totalCA'] / 100;
                } else {
                    $totalChargeCout += $infoCharge->getCout();
                }
            }

            $totalCharge[] = $totalChargeCout;
        }

        return $totalCharge;
    }

    public function getCurrentInternalOrder($idCatalog) {
        foreach ($this->getOrders() as $order) {
            if ($order->isOpen() && $order->getCatalog()->getId() == $idCatalog) {
                return $order;
            }
        }
        return null;
    }

    public function getCurrentInternalAdviceOrder() {
        foreach ($this->getOrders() as $order) {
            if ($order->isOpen() && $order->getCatalog()->getId() == Catalog::CATALOG_1H_EXPERT_FORMULA_ID) {
                return $order;
            }
        }
        return null;
    }

    public function getCurrentInternalPrezOrder() {
        foreach ($this->getOrders() as $order) {
            if ($order->isOpen() && $order->getCatalog()->getId() == Catalog::CATALOG_PREZ_PROJECT_FORMULA_ID) {
                return $order;
            }
        }
        return null;
    }

    /**
     * Get the value of orders
     *
     * @return  BackBundle\Entity\JoorneyOrder
     */
    public function getOrders() {
        return $this->orders;
    }

    /**
     * Set the value of orders
     *
     * @param  BackBundle\Entity\JoorneyOrder  $orders
     *
     */
    public function setOrders($orders) {
        $this->orders = $orders;
    }

    /**
     * Get the value of commandeCatalogs
     *
     * @return  CommandeCatalog
     */ 
    public function getCommandeCatalogs()
    {
        return $this->commandeCatalogs;
    }

    /**
     * Set the value of commandeCatalogs
     *
     * @param  CommandeCatalog  $commandeCatalogs
     *
     * @return  self
     */ 
    public function setCommandeCatalogs($commandeCatalogs)
    {
        $this->commandeCatalogs = $commandeCatalogs;

        return $this;
    }
}
