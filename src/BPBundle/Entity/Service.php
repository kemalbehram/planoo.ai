<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Service
 *
 * @ORM\Table(name="bp_service")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\ServiceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Service {

    /**
     * Constructor
     */
    public function __construct() {
        $this->expireEditDate = null;
        $this->adviceHour = 0;
        $this->nbWording = 0;
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
     * @var Date
     *
     * @ORM\Column(name="expire_edit_date", type="datetime", nullable=true)
     */
    private $expireEditDate;

    /**
     * @var Date
     *
     * @ORM\Column(name="expire_trial_date", type="datetime", nullable=true)
     */
    private $expireTrialDate;

    /**
     * @var int
     *
     * @ORM\Column(name="advice_hour", type="float")
     */
    private $adviceHour;

        /**
     * @var int
     *
     * @ORM\Column(name="nb_wording", type="float")
     */
    private $nbWording;

    /**
     * @ORM\OneToOne(targetEntity="BusinessPlan", inversedBy="service", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="businessPlan_id", referencedColumnName="id",nullable=true,onDelete="CASCADE")
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

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="services")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set remainingTime
     *
     * @param Date $expireEditDate
     *
     * @return Service
     */
    public function setExpireEditDate($expireEditDate) {
        $this->expireEditDate = $expireEditDate;

        return $this;
    }

    /**
     * Get expireEditDate
     *
     * @return Date
     */
    public function getExpireEditDate() {
        return $this->expireEditDate;
    }

    /**
     * Set nbWording
     *
     * @param integer $advicenbWordingHour
     *
     * @return Service
     */
    public function setNbWording($nbWording) {
        $this->nbWording = $nbWording;

        return $this;
    }

    /**
     * Get nbWording
     *
     * @return int
     */
    public function getNbWording() {
        return $this->nbWording;
    }

    /**
     * Set adviceHour
     *
     * @param integer $adviceHour
     *
     * @return Service
     */
    public function setAdviceHour($adviceHour) {
        $this->adviceHour = $adviceHour;

        return $this;
    }

    /**
     * Get adviceHour
     *
     * @return int
     */
    public function getAdviceHour() {
        return $this->adviceHour;
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Cart
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

    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    public function setBusinessPlan($businessPlan) {
        $this->businessPlan = $businessPlan;
    }

    public function getExpireTrialDate() {
        return $this->expireTrialDate;
    }

    public function setExpireTrialDate($expireTrialDate) {
        $this->expireTrialDate = $expireTrialDate;
    }

}
