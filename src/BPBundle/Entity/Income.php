<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Income
 *
 * @ORM\Table(name="bp_income")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\IncomeRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Income
{
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
     * @ORM\Column(name="name", type="string", length=150)
     * @Assert\NotBlank(message="attention",groups={"income"})
     */
    protected $name;


    /**
     * @var array
     *
     * @ORM\Column(name="sale", type="json_array", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"income"})
     */
    protected $sale;

    /**
     * @var array
     *
     * @ORM\Column(name="single_price", type="json_array", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"income"})
     */
    protected $singlePrice;

    /**
     * @var array
     *
     * @ORM\Column(name="cost", type="json_array", nullable=true)
     * @Assert\NotBlank(message="attention",groups={"income"})
     */
    protected $cost;

    /**
     * @var string
     *
     * @ORM\Column(name="untitled", type="string", length=150, nullable=true)
     */
    protected $untitled;

    /**
     * @var array
     *
     * @ORM\Column(name="commission", type="json_array", nullable=true)
     */
    protected $commission;

    /**
     * @var string
     *
     * @ORM\Column(name="directCost", type="string", length=150, nullable=true)
     */
    protected $directCost;


    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="incomes")
     */
    protected $user;


    /**
     * @var BusinessPlan
     * @ORM\ManyToOne(targetEntity="BusinessPlan", inversedBy="incomes", cascade={"persist"})
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Income
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sale
     *
     * @param array $sale
     *
     * @return Income
     */
    public function setSale($sale)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return array
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set singlePrice
     *
     * @param array $singlePrice
     *
     * @return Income
     */
    public function setSinglePrice($singlePrice)
    {
        $this->singlePrice = $singlePrice;

        return $this;
    }

    /**
     * Get singlePrice
     *
     * @return array
     */
    public function getSinglePrice()
    {
        return $this->singlePrice;
    }

    /**
     * Set cost
     *
     * @param array $cost
     *
     * @return Income
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return array
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set untitled
     *
     * @param string $untitled
     *
     * @return Income
     */
    public function setUntitled($untitled)
    {
        $this->untitled = $untitled;

        return $this;
    }

    /**
     * Get untitled
     *
     * @return string
     */
    public function getUntitled()
    {
        return $this->untitled;
    }

    /**
     * Set commission
     *
     * @param array $commission
     *
     * @return Income
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return array
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Set directCost
     *
     * @param string $directCost
     *
     * @return Income
     */
    public function setDirectCost($directCost)
    {
        $this->directCost = $directCost;

        return $this;
    }

    /**
     * Get directCost
     *
     * @return string
     */
    public function getDirectCost()
    {
        return $this->directCost;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Income
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set businessPlan
     *
     * @param \BPBundle\Entity\BusinessPlan $businessPlan
     *
     * @return Income
     */
    public function setBusinessPlan(\BPBundle\Entity\BusinessPlan $businessPlan = null)
    {
        $this->businessPlan = $businessPlan;

        return $this;
    }

    /**
     * Get businessPlan
     *
     * @return \BPBundle\Entity\BusinessPlan
     */
    public function getBusinessPlan()
    {
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
