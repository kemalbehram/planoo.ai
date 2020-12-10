<?php

namespace BPBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Saisonnalite
 *
 * @ORM\Table(name="bp_saisonnalite")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\SaisonnaliteRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Saisonnalite {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomMois", type="string", length=255, nullable=true)
     */
    private $nomMois;

    /**
     * @var float
     *
     * @ORM\Column(name="saisonCA", type="float", nullable=true)
     */
    private $saisonCA;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BusinessPlan", inversedBy="saisonnalites", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $businessPlan;

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
    public static $nomsMois = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];
    private static $nombreInstances = 0;

    /**
     * Constructor
     */
    public function __construct() {
        $this->saisonCA = 50;

        self::$nombreInstances++;
        $this->setNomMois(self::$nomsMois[self::$nombreInstances]);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nomMois
     *
     * @param string $nomMois
     *
     * @return Mensualite
     */
    public function setNomMois($nomMois) {
        $this->nomMois = $nomMois;

        return $this;
    }

    /**
     * Get nomMois
     *
     * @return string
     */
    public function getNomMois() {
        return $this->nomMois;
    }

    /**
     * Set saisonCA
     *
     * @param float $saisonCA
     *
     * @return Mensualite
     */
    public function setSaisonCA($saisonCA) {
        $this->saisonCA = $saisonCA;

        return $this;
    }

    /**
     * Get saisonCA
     *
     * @return float
     */
    public function getSaisonCA() {
        return $this->saisonCA;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Saisonnalite
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
