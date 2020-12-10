<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Bfr
 *
 * @ORM\Table(name="bp_bfr")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\BfrRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Bfr {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Délai de paiement théorique des clients (en jours)
     * @var float
     * 
     * @ORM\Column(name="customer", type="float")
     * @Assert\NotBlank()
     */
    protected $customer;

    /**
     * Délai de paiement théorique des fournisseurs (en nbre de jours d'achats)
     * @var float
     *
     * @ORM\Column(name="provider", type="float")
     * @Assert\NotBlank()
     */
    protected $provider;

    /**
     * Estimation des avances et acomptes reçus des clients (en % des paiements)
     * @var float
     *
     * @ORM\Column(name="acpte_customer", type="float")
     * @Assert\NotBlank()
     */
    protected $acpteCustomer;

    /**
     * Estimation des avances et acomptes à verser aux fournisseurs (en % des paiements)
     * @var float
     *
     * @ORM\Column(name="acpte_provider", type="float")
     * @Assert\NotBlank()
     */
    protected $acpteProvider;

    /**
     * Stock (en jours de marchandises vendues)
     * @var float
     *
     * @ORM\Column(name="stock", type="float")
     * @Assert\NotBlank()
     */
    protected $stock;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="bfrs")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="BPBundle\Entity\BusinessPlan",inversedBy="infoBfr", cascade={"persist", "remove"})
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

    function __construct() {
        $this->setAcpteCustomer(0);
        $this->setAcpteProvider(0);
        $this->setCustomer(0);
        $this->setProvider(0);
        $this->setStock(0);
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
     * Set customer
     *
     * @param float $customer
     *
     * @return Bfr
     */
    public function setCustomer($customer) {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return float
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * Set provider
     *
     * @param float $provider
     *
     * @return Bfr
     */
    public function setProvider($provider) {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return float
     */
    public function getProvider() {
        return $this->provider;
    }

    /**
     * Set acpteCustomer
     *
     * @param float $acpteCustomer
     *
     * @return Bfr
     */
    public function setAcpteCustomer($acpteCustomer) {
        $this->acpteCustomer = $acpteCustomer;

        return $this;
    }

    /**
     * Get acpteCustomer
     *
     * @return float
     */
    public function getAcpteCustomer() {
        return $this->acpteCustomer;
    }

    /**
     * Set acpteProvider
     *
     * @param float $acpteProvider
     *
     * @return Bfr
     */
    public function setAcpteProvider($acpteProvider) {
        $this->acpteProvider = $acpteProvider;

        return $this;
    }

    /**
     * Get acpteProvider
     *
     * @return float
     */
    public function getAcpteProvider() {
        return $this->acpteProvider;
    }

    /**
     * Set stock
     *
     * @param float $stock
     *
     * @return Bfr
     */
    public function setStock($stock) {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return float
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Bfr
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
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Bfr
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
