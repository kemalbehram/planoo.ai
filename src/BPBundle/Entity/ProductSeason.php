<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProductSeason
 *
 * @ORM\Table(name="product_season")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ProductSeasonRepository")
 */
class ProductSeason {

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
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="productSeasons")
     *
     */
    private $produit;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct(Produit $produit, String $nomMois, float $saisonCA) {
        $this->produit = $produit;
        $this->nomMois = $nomMois;
        $this->saisonCA = $saisonCA;
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
