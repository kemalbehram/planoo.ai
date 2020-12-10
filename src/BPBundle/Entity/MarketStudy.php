<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarketStudy
 *
 * @ORM\Table(name="bp_wording_marketstudy")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\MarketStudyRepository")
 */
class MarketStudy {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="BusinessPlan", inversedBy="marketStudy", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="businessPlan_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $businessPlan;

    /**
     * @var string
     *
     * @ORM\Column(name="mainMarket", type="string", length=255)
     */
    private $mainMarket;

    /**
     * @var array
     *
     * @ORM\Column(name="marketSize", type="string", length=255)
     */
    private $marketSize;

    /**
     * @var array
     *
     * @ORM\Column(name="hasMarketPlace", type="boolean")
     */
    private $hasMarketPlace;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="BPBundle\Entity\Address", cascade={"persist", "remove"})
     */
    protected $addressMarketPlace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transportDurationHours", type="integer", nullable=true)
     */
    protected $transportDurationHours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transportDurationMinutes", type="integer", nullable=true)
     */
    protected $transportDurationMinutes;

    /**
     * @var array
     *
     * @ORM\Column(name="transportMode", type="string", length=255)
     */
    private $transportMode;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set mainMarket
     *
     * @param string $mainMarket
     *
     * @return MarketStudy
     */
    public function setMainMarket($mainMarket) {
        $this->mainMarket = $mainMarket;

        return $this;
    }

    /**
     * Get mainMarket
     *
     * @return string
     */
    public function getMainMarket() {
        return $this->mainMarket;
    }

    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    public function setBusinessPlan($businessPlan) {
        $this->businessPlan = $businessPlan;
    }


    /**
     * Get the value of marketSize
     *
     */ 
    public function getMarketSize()
    {
        return $this->marketSize;
    }

    /**
     * Set the value of marketSize
     *
     *
     * @return  self
     */ 
    public function setMarketSize($marketSize)
    {
        $this->marketSize = $marketSize;

        return $this;
    }
    
    public function getAddressMarketPlace() {
        return $this->addressMarketPlace;
    }

    public function setAddressMarketPlace($address) {
        $this->addressMarketPlace = $address;
    }

    public function getHasMarketPlace() {
        return $this->hasMarketPlace;
    }

    public function setHasMarketPlace($hasMarketPlace) {
        $this->hasMarketPlace = $hasMarketPlace;
    }

    /**
     * Get the value of transportMode
     *
     */ 
    public function getTransportMode()
    {
        return $this->transportMode;
    }

    /**
     * Set the value of transportMode
     *
     *
     * @return  self
     */ 
    public function setTransportMode($transportMode)
    {
        $this->transportMode = $transportMode;

        return $this;
    }

    public function getTransportDurationHours()
    {
        return $this->transportDurationHours;
    }

    public function setTransportDurationHours($transportDurationHours)
    {
        $this->transportDurationHours = $transportDurationHours;

        return $this;
    }

    public function getTransportDurationMinutes()
    {
        return $this->transportDurationMinutes;
    }

    public function setTransportDurationMinutes($transportDurationMinutes)
    {
        $this->transportDurationMinutes = $transportDurationMinutes;

        return $this;
    }
}
