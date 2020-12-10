<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Staff
 *
 * @ORM\Table(name="bp_staff")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\StaffRepository")*
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Staff {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var float
     *
     * @ORM\Column(name="income", type="float")
     * @Assert\NotBlank(message="attention",groups={"staff"})
     */
    protected $income;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank(message="attention",groups={"staff"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     */
    protected $finishedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="hours", type="integer", length=255, nullable=true)
     * @Assert\NotBlank(message="attention",groups={"staff"})
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La valeur doit être comprise entre 0 et 100 (%)",
     *      maxMessage = "La valeur doit être comprise entre 0 et 100 (%)",
     *      groups={"staff"}
     * )
     */
    protected $hours;

    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\BusinessPlan", inversedBy="staffs", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $businessPlan;

    /**
     * @var Pole
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Pole", inversedBy="staffs", cascade={"persist"})
     */
    protected $pole;

    /**
     * @var Status
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Status", inversedBy="staffs", cascade={"persist"})
     */
    protected $status;
    protected $detailMasseSalariale;
    protected $detailMassePatronale;
    protected $chargeSociale;
    protected $chargeSocialeTNS;

    /**
     * @ORM\column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $staffCreatedAt;

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
     * Set name
     *
     * @param string $name
     *
     * @return Staff
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set income
     *
     * @param float $income
     *
     * @return Staff
     */
    public function setIncome($income) {
        $this->income = $income;

        return $this;
    }

    /**
     * Get income
     *
     * @return float
     */
    public function getIncome() {
        return $this->income;
    }

    /**
     * Set staffCreatedAt
     *
     * @param \DateTime $staffCreatedAt
     *
     * @return Staff
     */
    public function setStaffCreatedAt($staffCreatedAt) {
        $this->staffCreatedAt = $staffCreatedAt;

        return $this;
    }

    /**
     * Get staffCreatedAt
     *
     * @return \DateTime
     */
    public function getStaffCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     *
     * @return Staff
     */
    public function setFinishedAt($finishedAt) {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime
     */
    public function getFinishedAt() {
        return $this->finishedAt;
    }

    /**
     * Set hours
     *
     * @param string $hours
     *
     * @return Staff
     */
    public function setHours($hours) {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return string
     */
    public function getHours() {
        return $this->hours;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Staff
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
     * Set pole
     *
     * @param \BPBundle\Entity\Pole $pole
     *
     * @return Staff
     */
    public function setPole(\BPBundle\Entity\Pole $pole = null) {
        $this->pole = $pole;

        return $this;
    }

    /**
     * Get pole
     *
     * @return \BPBundle\Entity\Pole
     */
    public function getPole() {
        return $this->pole;
    }

    /**
     * Set status
     *
     * @param \BPBundle\Entity\Status $status
     *
     * @return Staff
     */
    public function setStatus(\BPBundle\Entity\Status $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BPBundle\Entity\Status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getDetailMasseSalariale() {
        return $this->detailMasseSalariale;
    }

    /**
     * @param $detail
     * @return $this
     */
    public function setDetailMasseSalariale($detail) {
        $this->detailMasseSalariale = $detail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetailMassePatronale() {
        return $this->detailMassePatronale;
    }

    /**
     * @param $detail
     * @return $this
     */
    public function setDetailMassePatronale($detail) {
        $this->detailMassePatronale = $detail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChargeSociale() {
        return $this->chargeSociale;
    }

    /**
     * @param $chargeSociale
     * @return $this
     */
    public function setChargeSociale($chargeSociale) {
        $this->chargeSociale = $chargeSociale;

        return $this;
    }

    public function setChargeSocialeTNS($chargeSocialeTNS) {
        $this->chargeSocialeTNS = $chargeSocialeTNS;
    }

    public function getChargeSocialeTNS() {
        return $this->chargeSocialeTNS;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload) {
        $dateDebutBP = $this->getBusinessPlan()->getInformation()->getCreatedAt();
        $dateFinBP = $this->getBusinessPlan()->getInformation()->getClosingDate();

        if ($this->getCreatedAt() < $dateDebutBP || $this->getCreatedAt() > $dateFinBP) {
            $context->buildViolation("La date d'embauche n'est pas valide")
                    ->atPath('createdAt')
                    ->addViolation();
        }

        if (!empty($this->getFinishedAt()) && ($this->getFinishedAt() < $this->getCreatedAt() || $this->getFinishedAt() > $dateFinBP)) {
            $context->buildViolation("La date de fin d'embauche n'est pas valide")
                    ->atPath('finishedAt')
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
