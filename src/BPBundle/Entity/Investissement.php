<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Investissement
 *
 * @ORM\Table(name="bp_investissement")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\InvestissementRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Investissement {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="attention",groups={"investissement"})
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var float
     * 
     * @ORM\Column(name="amount", type="float")
     * @Assert\NotBlank(message="attention",groups={"investissement"})
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Le montant doit être supérieur ou égal à 1",
     *      groups={"investissement"}
     * )
     */
    protected $amount;
    protected $baseAmortissement;

    /**
     * @var ChargeLabel
     * @Assert\NotBlank(message="attention",groups={"investissement"})
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\ChargeLabel", inversedBy="investissements", cascade={"persist"})
     */
    protected $chargeLabel;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="investissements", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $businessPlan;
    protected $detailImmoNet;
	
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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Investissement
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Investissement
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Investissement
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
     * Set chargeLabel
     *
     * @param \BPBundle\Entity\ChargeLabel $chargeLabel
     *
     * @return Investissement
     */
    public function setChargeLabel(\BPBundle\Entity\ChargeLabel $chargeLabel = null) {
        $this->chargeLabel = $chargeLabel;

        return $this;
    }

    /**
     * Get chargeLabel
     *
     * @return \BPBundle\Entity\ChargeLabel
     */
    public function getChargeLabel() {
        return $this->chargeLabel;
    }

    /**
     * Set setDetailImmoNet
     *
     * @param $detail
     *
     * @return Investissement
     */
    public function setDetailImmoNet($detail) {
        $this->detailImmoNet = $detail;

        return $this;
    }

    /**
     * Get getDetailImmoNet
     */
    public function getDetailImmoNet() {
        return $this->detailImmoNet;
    }

    public function getBaseAmortissement() {
        return $this->baseAmortissement;
    }

    public function setBaseAmortissement($baseAmortissement) {
        $this->baseAmortissement = $baseAmortissement;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload) {
        $dateDebutBP = $this->getBusinessPlan()->getInformation()->getCreatedAt();
        $dateFinBP = $this->getBusinessPlan()->getInformation()->getClosingDate();

        if ($this->getDate() < $dateDebutBP || $this->getDate() > $dateFinBP) {
            $context->buildViolation("La date d'investissement n'est pas valide")
                    ->atPath('date')
                    ->addViolation();
        }
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
