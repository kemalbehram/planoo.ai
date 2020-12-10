<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Exercice
 *
 * @ORM\Table(name="bp_exercice")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ExerciceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false) 
 */
class Exercice {

    private static $compteur = 0;
    private $chargeFixeMensuel;
    private $ca;
    private $rcai;
    private $rcaiNet;
    private $is;
    private $chargesTNS;
    private $chargesTNSHorsRCAI;
    private $masseSalarialeTNS = 0;
    private $reserve;
    private $isApresAcompte;
    private $chargesTNSApresAcompte;
    private $deficitReportable = 0;
    private $cumuleIsPnl = 0;
    private $cumuleChargesTNSPnl = 0;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

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
     * Constructor
     */
    public function __construct() {
        $this->ca = 0;
        $this->rcai = 0;
        $this->rcaiNet = 0;
        $this->is = 0;
        $this->chargesTNS = 0;
        self::$compteur++;
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime", nullable=true)
     */
    protected $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    protected $dateFin;

    /**
     * @var InfoProduct
     * @ORM\OneToMany(targetEntity="InfoProduct", mappedBy="exercice", cascade={"remove"}, orphanRemoval=true)
     *
     */
    private $infoProduct;

    /**
     * @var InfoCharge
     * @ORM\OneToMany(targetEntity="InfoCharge", mappedBy="exercice", cascade={"remove"}, orphanRemoval=true)
     *
     */
    private $infoCharge;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="exercices")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE"))
     */
    protected $businessPlan;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Exercice
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Exercice
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin() {
        return $this->dateFin;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Exercice
     */
    public function setBusinessPlan(\BPBundle\Entity\BusinessPlan $businessPlan = null) {
        $this->businessPlan = $businessPlan;

        return $this;
    }

    /**
     * Get businessPlan
     *
     * @return \BPBundle\Entity\BusinessPlan
     */
    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    public function getCompteur() {
        return self::$compteur;
    }

    /**
     * Add infoProduct
     *
     * @param \BPBundle\Entity\InfoProduct $infoProduct
     *
     * @return Exercice
     */
    public function addInfoProduct(\BPBundle\Entity\InfoProduct $infoProduct) {
        $this->infoProduct[] = $infoProduct;

        return $this;
    }

    /**
     * Remove infoProduct
     *
     * @param \BPBundle\Entity\InfoProduct $infoProduct
     */
    public function removeInfoProduct(\BPBundle\Entity\InfoProduct $infoProduct) {
        $this->infoProduct->removeElement($infoProduct);
    }

    /**
     * Get infoProduct
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoProduct() {
        return $this->infoProduct;
    }

    /**
     * Add infoCharge
     *
     * @param \BPBundle\Entity\InfoCharge $infoCharge
     *
     * @return Exercice
     */
    public function addInfoCharge(\BPBundle\Entity\InfoCharge $infoCharge) {
        $this->infoCharge[] = $infoCharge;

        return $this;
    }

    /**
     * Remove infoCharge
     *
     * @param \BPBundle\Entity\InfoCharge $infoCharge
     */
    public function removeInfoCharge(\BPBundle\Entity\InfoCharge $infoCharge) {
        $this->infoCharge->removeElement($infoCharge);
    }

    /**
     * Get infoCharge
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfoCharge() {
        return $this->infoCharge;
    }

    /**
     * @return mixed
     */
    public function getChargeFixeMensuel() {
        return $this->chargeFixeMensuel;
    }

    /**
     * @param mixed $chargeFixeMensuel
     */
    public function setChargeFixeMensuel($chargeFixeMensuel) {
        $this->chargeFixeMensuel = $chargeFixeMensuel;

        return $this;
    }

    public function getMasseSalarialeTNS() {
        return $this->masseSalarialeTNS;
    }

    public function setMasseSalarialeTNS($masseSalarialeTNS) {
        $this->masseSalarialeTNS = $masseSalarialeTNS;
    }

    /**
     * @return int
     */
    public function getCa() {
        return $this->ca;
    }

    /**
     * @param int $ca
     */
    public function setCa($ca) {
        $this->ca = $ca;

        return $this;
    }

    /**
     * @return int
     */
    public function getRcai() {
        return $this->rcai;
    }

    /**
     * @param int $rcai
     */
    public function setRcai($rcai) {
        $this->rcai = $rcai;

        return $this;
    }

    /**
     * @return int
     */
    public function getChargesTNS() {
        return $this->chargesTNS;
    }

    /**
     * @param int $chargesTNS
     */
    public function setChargesTNS($chargesTNS) {
        $this->chargesTNS = $chargesTNS;
        $this->chargesTNSApresAcompte = $chargesTNS;
        return $this;
    }

    public function getChargesTNSHorsRCAI() {
        return $this->chargesTNSHorsRCAI;
    }

    public function setChargesTNSHorsRCAI($chargesTNSHorsRCAI) {
        $this->chargesTNSHorsRCAI = $chargesTNSHorsRCAI;

        return $this;
    }

    /**
     * @return int
     */
    public function getIs() {
        return $this->is;
    }

    /**
     * @param int $is
     */
    public function setIs($is) {
        $this->is = $is;
        $this->isApresAcompte = $is;

        return $this;
    }

    /**
     * @return int
     */
    public function getIsApresAcompte() {
        return $this->isApresAcompte;
    }

    /**
     * @param int $isApresAcompte
     */
    public function setIsApresAcompte($isApresAcompte) {
        $this->isApresAcompte = $isApresAcompte;

        return $this;
    }

    public function getChargesTNSApresAcompte() {
        return $this->chargesTNSApresAcompte;
    }

    public function setChargesTNSApresAcompte($chargesTNSApresAcompte) {
        $this->chargesTNSApresAcompte = $chargesTNSApresAcompte;
    }

    /**
     * @return int
     */
    public function getDeficitReportable() {
        return $this->deficitReportable;
    }

    /**
     * @param int $deficitReportable
     */
    public function setDeficitReportable($deficitReportable) {
        $this->deficitReportable = $deficitReportable;

        return $this;
    }

    /**
     * @return int
     */
    public function getRcaiNet() {
        return $this->rcaiNet;
    }

    /**
     * @param int $rcaiNet
     */
    public function setRcaiNet($rcaiNet) {
        $this->rcaiNet = $rcaiNet;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCumuleIsPnl() {
        return $this->cumuleIsPnl;
    }

    /**
     * @param mixed $cumuleIsPnl
     */
    public function setCumuleIsPnl($cumuleIsPnl) {
        $this->cumuleIsPnl = $cumuleIsPnl;

        return $this;
    }

    public function getCumuleChargesTNSPnl() {
        return $this->cumuleChargesTNSPnl;
    }

    public function setCumuleChargesTNSPnl($cumuleChargesTNSPnl) {
        $this->cumuleChargesTNSPnl = $cumuleChargesTNSPnl;
    }

    /**
     * @return mixed
     */
    public function getReserve() {
        return $this->reserve;
    }

    /**
     * @param mixed $reserve
     */
    public function setReserve($reserve) {
        $this->reserve = $reserve;

        return $this;
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

}
