<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProductStockSeason
 *
 * @ORM\Table(name="product_stock_season")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ProductStockSeasonRepository")
 */
class ProductStockSeason {

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
     * @ORM\Column(name="saisonNbDStockDays", type="integer", nullable=true)
     */
    private $saisonNbStockDays;

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
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="productStockSeasons")
     *
     */
    private $produit;

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
    public function __construct(Produit $produit, int $saisonNbStockDays) {
        $this->produit = $produit;
        $this->saisonNbStockDays = $saisonNbStockDays;

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
     * Set saisonNbStockDays
     *
     * @param integer $saisonNbStockDays
     *
     * @return Mensualite
     */
    public function setSaisonNbStockDays($saisonNbStockDays) {
        $this->saisonNbStockDays = $saisonNbStockDays;

        return $this;
    }

    /**
     * Get saisonCA
     *
     * @return float
     */
    public function getSaisonNbStockDays() {
        return $this->saisonNbStockDays;
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

    /**
     * Set produit
     *
     * @param \BPBundle\Entity\Produit $produit
     *
     * @return InfoProduct
     */
    public function setProduit(\BPBundle\Entity\Produit $produit = null) {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \BPBundle\Entity\Produit
     */
    public function getProduit() {
        return $this->produit;
    }

}
