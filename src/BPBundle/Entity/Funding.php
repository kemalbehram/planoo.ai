<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Funding
 *
 * @ORM\Table(name="bp_funding")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\FundingRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Funding {

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
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank(message="Champs obligatoire",groups={"funding"})
     */
    protected $createdAt;

    /**
     * @var \integer
     *
     * @ORM\Column(name="within", type="integer")
     * @Assert\NotBlank(message="Champs obligatoire",groups={"duree"})
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "La durée minimum est d'1 mensualité",
     *      groups={"duree"}
     * )
     */
    protected $within;

    /**
     * @var \integer
     *
     * @ORM\Column(name="differe", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "La différé minimum doit être supérieur à 0",
     *      groups={"funding"}
     * )
     */
    protected $differe;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     * @Assert\NotBlank(message="Champs obligatoire",groups={"duree"})
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Le montant emprunté doit être supérieur à 0€",
     *      groups={"duree"}
     * )
     */
    protected $amount;
    protected $amountTmp;
    protected $amountTresorerieTmp;
    protected $amountSubventionTmp;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float")
     * @Assert\NotBlank(message="Champs obligatoire",groups={"taux"})
     * @Assert\Range(
     *      min = 0,
     *      minMessage = "Le taux doit être supérieur ou égal à 0%",
     *      groups={"taux"}
     * )
     */
    protected $rate;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="fundings", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $businessPlan;

    /**
     * @var ChargeLabel
     * @Assert\NotBlank(message="Champs obligatoire",groups={"funding"})
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\ChargeLabel", inversedBy="fundings", cascade={"persist"})
     */
    protected $chargeLabel;
    protected $detailEmpruntBancaire;
    protected $detailSubventionRemboursable;
    protected $detailCompteAssocie;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $fundingCreatedAt;

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

    public function setId($id) {
        return $this->id = $id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Funding
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
     * Set within
     *
     * @param string $within
     *
     * @return Funding
     */
    public function setWithin($within) {
        $this->within = $within;

        return $this;
    }

    /**
     * Get within
     *
     * @return string
     */
    public function getWithin() {
        return $this->within;
    }

    public function getDiffere() {
        return $this->differe;
    }

    public function setDiffere($differe) {
        $this->differe = $differe;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Funding
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
     * Set rate
     *
     * @param float $rate
     *
     * @return Funding
     */
    public function setRate($rate) {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Funding
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
     * @return Funding
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
     * @return Funding
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
     * @return mixed
     */
    public function getAmountTmp() {
        return $this->amountTmp;
    }

    /**
     * @param mixed $amountTmp
     */
    public function setAmountTmp($amountTmp) {
        $this->amountTmp = $amountTmp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmountTresorerieTmp() {
        return $this->amountTresorerieTmp;
    }

    /**
     * @param mixed $amountTresorerieTmp
     */
    public function setAmountTresorerieTmp($amountTresorerieTmp) {
        $this->amountTresorerieTmp = $amountTresorerieTmp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmountSubventionTmp() {
        return $this->amountSubventionTmp;
    }

    /**
     * @param mixed $amountSubventionTmp
     */
    public function setAmountSubventionTmp($amountSubventionTmp) {
        $this->amountSubventionTmp = $amountSubventionTmp;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload) {
        $dateDebutBP = $this->getBusinessPlan()->getInformation()->getCreatedAt();
        $dateFinBP = $this->getBusinessPlan()->getInformation()->getClosingDate();

        if ($this->getCreatedAt() < $dateDebutBP || $this->getCreatedAt() > $dateFinBP) {
            $context->buildViolation("La date de financement n'est pas valide")
                    ->atPath('createdAt')
                    ->addViolation();
        }
    }

    /**
     * @return mixed
     */
    public function getDetailEmpruntBancaire() {
        return $this->detailEmpruntBancaire;
    }

    /**
     * @param mixed $detailEmpruntBancaire
     * @return Funding
     */
    public function setDetailEmpruntBancaire($detailEmpruntBancaire) {
        $this->detailEmpruntBancaire = $detailEmpruntBancaire;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailSubventionRemboursable() {
        return $this->detailSubventionRemboursable;
    }

    /**
     * @param mixed $detailSubventionRemboursable
     * @return Funding
     */
    public function setDetailSubventionRemboursable($detailSubventionRemboursable) {
        $this->detailSubventionRemboursable = $detailSubventionRemboursable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailCompteAssocie() {
        return $this->detailCompteAssocie;
    }

    /**
     * @param mixed $detailCompteAssocie
     * @return Funding
     */
    public function setDetailCompteAssocie($detailCompteAssocie) {
        $this->detailCompteAssocie = $detailCompteAssocie;

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
     * Set fundingCreatedAt
     *
     * @param \DateTime $fundingCreatedAt
     *
     * @return BusinessPlan
     */
    public function setFundingCreatedAt($fundingCreatedAt) {
        $this->fundingCreatedAt = $fundingCreatedAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getFundingCreatedAt() {
        return $this->fundingCreatedAt;
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
