<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ChargeLabel
 *
 * @ORM\Table(name="bp_charge_label")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ChargeLabelRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class ChargeLabel {

    use ORMBehaviors\Translatable\Translatable;

    /**
     * Constructor
     */
    public function __construct() {
        $this->charges = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fundings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->amortissements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->investissements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isAugmentationCapital = false;
        $this->isCompteCourantAssocie = false;
        $this->isDividende = false;
        $this->isEmpruntBancaire = false;
        $this->isInteret = false;
        $this->isNonRemboursable = false;
        $this->isRemboursable = false;
        $this->isRemboursementCompteAssocie = false;
        $this->displayTaux = false;
        $this->displayDuree = false;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="display_order", type="integer",  options={"default"=999999999})
     */
    private $displayOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="classement", type="string", length=255)
     */
    private $classement;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_interet", type="boolean")
     */
    private $isInteret;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_remboursable", type="boolean")
     */
    private $isRemboursable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_non_remboursable", type="boolean")
     */
    private $isNonRemboursable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_emprunt_bancaire", type="boolean")
     */
    private $isEmpruntBancaire;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_compte_courant_associe", type="boolean")
     */
    private $isCompteCourantAssocie;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_remboursement_courant_associe", type="boolean")
     */
    private $isRemboursementCompteAssocie;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_augmentation_capital", type="boolean")
     */
    private $isAugmentationCapital;

    /**
     * @var boolean
     *
     * @ORM\Column(name="id_dividende", type="boolean")
     */
    private $isDividende;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var Charge
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Charge", mappedBy="chargeLabel")
     */
    protected $charges;

    /**
     * @var Funding
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Funding", mappedBy="chargeLabel")
     */
    protected $fundings;

    /**
     * @var Amortissement
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Amortissement", mappedBy="chargeLabel")
     */
    protected $amortissements;

    /**
     * @var Investissement
     * @ORM\OneToMany(targetEntity="BPBundle\Entity\Investissement", mappedBy="chargeLabel")
     */
    protected $investissements;

    /**
     * @var boolean
     *
     * @ORM\Column(name="display_taux", type="boolean")
     */
    private $displayTaux;

    /**
     * @var boolean
     *
     * @ORM\Column(name="display_duree", type="boolean")
     */
    private $displayDuree;

    public function getDisplayOrder() {
        return $this->displayOrder;
    }

    public function setDisplayOrder($displayOrder) {
        $this->displayOrder = $displayOrder;
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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return ChargeLabel
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
     * Add charge
     *
     * @param \BPBundle\Entity\Charge $charge
     *
     * @return ChargeLabel
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
        return $this->charges;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ChargeLabel
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Add funding
     *
     * @param \BPBundle\Entity\Funding $funding
     *
     * @return ChargeLabel
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
     * @return mixed
     */
    public function getClassement() {
        return $this->classement;
    }

    /**
     * @param mixed $classement
     */
    public function setClassement($classement) {
        $this->classement = $classement;
    }

    /**
     * Add amortissement
     *
     * @param \BPBundle\Entity\Amortissement $amortissement
     *
     * @return ChargeLabel
     */
    public function addAmortissement(\BPBundle\Entity\Amortissement $amortissement) {
        $this->amortissements[] = $amortissement;

        return $this;
    }

    /**
     * Remove amortissement
     *
     * @param \BPBundle\Entity\Amortissement $amortissement
     */
    public function removeAmortissement(\BPBundle\Entity\Amortissement $amortissement) {
        $this->amortissements->removeElement($amortissement);
    }

    /**
     * Get amortissements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAmortissements() {
        return $this->amortissements;
    }

    /**
     * Add investissement
     *
     * @param \BPBundle\Entity\Investissement $investissement
     *
     * @return ChargeLabel
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
     * @return mixed
     */
    public function getIsInteret() {
        return $this->isInteret;
    }

    /**
     * @param mixed $isInteret
     */
    public function setIsInteret($isInteret) {
        $this->isInteret = $isInteret;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRemboursable() {
        return $this->isRemboursable;
    }

    /**
     * @param mixed $isRemboursable
     */
    public function setIsRemboursable($isRemboursable) {
        $this->isRemboursable = $isRemboursable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsNonRemboursable() {
        return $this->isNonRemboursable;
    }

    /**
     * @param boolean $isNonRemboursable
     */
    public function setIsNonRemboursable($isNonRemboursable) {
        $this->isNonRemboursable = $isNonRemboursable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsEmpruntBancaire() {
        return $this->isEmpruntBancaire;
    }

    /**
     * @param mixed $isEmpruntBancaire
     */
    public function setIsEmpruntBancaire($isEmpruntBancaire) {
        $this->isEmpruntBancaire = $isEmpruntBancaire;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCompteCourantAssocie() {
        return $this->isCompteCourantAssocie;
    }

    /**
     * @param boolean $isCompteCourantAssocie
     */
    public function setIsCompteCourantAssocie($isCompteCourantAssocie) {
        $this->isCompteCourantAssocie = $isCompteCourantAssocie;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRemboursementCompteAssocie() {
        return $this->isRemboursementCompteAssocie;
    }

    /**
     * @param boolean $isRemboursementCompteAssocie
     */
    public function setIsRemboursementCompteAssocie($isRemboursementCompteAssocie) {
        $this->isRemboursementCompteAssocie = $isRemboursementCompteAssocie;
    }

    /**
     * @return mixed
     */
    public function getIsAugmentationCapital() {
        return $this->isAugmentationCapital;
    }

    /**
     * @param mixed $isAugmentationCapital
     */
    public function setIsAugmentationCapital($isAugmentationCapital) {
        $this->isAugmentationCapital = $isAugmentationCapital;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDividende() {
        return $this->isDividende;
    }

    /**
     * @param boolean $isDividende
     */
    public function setIsDividende($isDividende) {
        $this->isDividende = $isDividende;

        return $this;
    }

    /**
     * Set displayTaux
     *
     * @param boolean $displayTaux
     *
     * @return ChargeLabel
     */
    public function setDisplayTaux($displayTaux) {
        $this->displayTaux = $displayTaux;

        return $this;
    }

    /**
     * Get displayTaux
     *
     * @return boolean
     */
    public function getDisplayTaux() {
        return $this->displayTaux;
    }

    /**
     * Set displayDuree
     *
     * @param boolean $displayDuree
     *
     * @return ChargeLabel
     */
    public function setDisplayDuree($displayDuree) {
        $this->displayDuree = $displayDuree;

        return $this;
    }

    /**
     * Get displayDuree
     *
     * @return boolean
     */
    public function getDisplayDuree() {
        return $this->displayDuree;
    }

}
