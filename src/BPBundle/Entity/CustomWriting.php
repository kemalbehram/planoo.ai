<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomWriting
 *
 * @ORM\Table(name="bp_wording_customwriting")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\CustomWritingRepository")
 */
class CustomWriting {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="BusinessPlan", inversedBy="customWriting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="businessPlan_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $businessPlan;

    /**
     * @var string
     *
     * @ORM\Column(name="mainService1", type="string", length=255)
     */
    private $mainService1;

    /**
     * @var string
     *
     * @ORM\Column(name="mainService2", type="string", length=255, nullable=true)
     */
    private $mainService2;

    /**
     * @var string
     *
     * @ORM\Column(name="mainService3", type="string", length=255, nullable=true)
     */
    private $mainService3;

    /**
     * @var string
     *
     * @ORM\Column(name="mainService4", type="string", length=255, nullable=true)
     */
    private $mainService4;

    /**
     * @var string
     *
     * @ORM\Column(name="mainService5", type="string", length=255, nullable=true)
     */
    private $mainService5;

    /**
     * @var string
     *
     * @ORM\Column(name="mainProvider1", type="string", length=255, nullable=true)
     */
    private $mainProvider1;

    /**
     * @var string
     *
     * @ORM\Column(name="mainProvider2", type="string", length=255, nullable=true)
     */
    private $mainProvider2;

    /**
     * @var string
     *
     * @ORM\Column(name="mainProvider3", type="string", length=255, nullable=true)
     */
    private $mainProvider3;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPartner", type="string", length=255, nullable=true)
     */
    private $mainPartner1;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPartner2", type="string", length=255, nullable=true)
     */
    private $mainPartner2;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPartner3", type="string", length=255, nullable=true)
     */
    private $mainPartner3;

    /**
     * @var string
     *
     * @ORM\Column(name="mainTarget1", type="string", length=255)
     */
    private $mainTarget1;

    /**
     * @var string
     *
     * @ORM\Column(name="mainTarget2", type="string", length=255, nullable=true)
     */
    private $mainTarget2;

    /**
     * @var string
     *
     * @ORM\Column(name="mainTarget3", type="string", length=255, nullable=true)
     */
    private $mainTarget3;

    /**
     * @var string
     *
     * @ORM\Column(name="mainIntermediary1", type="string", length=255, nullable=true)
     */
    private $mainIntermediary1;

    /**
     * @var string
     *
     * @ORM\Column(name="mainIntermediary2", type="string", length=255, nullable=true)
     */
    private $mainIntermediary2;

    /**
     * @var string
     *
     * @ORM\Column(name="mainIntermediary3", type="string", length=255, nullable=true)
     */
    private $mainIntermediary3;

    /**
     * @var array
     *
     * @ORM\Column(name="mainChannels", type="array", nullable=true)
     */
    private $mainChannels;


    /**
     * @var array
     *
     * @ORM\Column(name="marketStrategies", type="array", nullable=true)
     */
    private $marketStrategies;

    /**
     * @var array
     *
     * @ORM\Column(name="communicationChannels", type="array", nullable=true)
     */
    private $communicationChannels;


    /**
     * @ORM\Column(name="cvFileName", type="string")
     */
    private $cvFileName;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set mainService1
     *
     * @param string $mainService1
     *
     * @return CustomWriting
     */
    public function setMainService1($mainService1) {
        $this->mainService1 = $mainService1;

        return $this;
    }

    /**
     * Get mainService1
     *
     * @return string
     */
    public function getMainService1() {
        return $this->mainService1;
    }

    /**
     * Set mainService2
     *
     * @param string $mainService2
     *
     * @return CustomWriting
     */
    public function setMainService2($mainService2) {
        $this->mainService2 = $mainService2;

        return $this;
    }

    /**
     * Get mainService2
     *
     * @return string
     */
    public function getMainService2() {
        return $this->mainService2;
    }

    /**
     * Set mainService3
     *
     * @param string $mainService3
     *
     * @return CustomWriting
     */
    public function setMainService3($mainService3) {
        $this->mainService3 = $mainService3;

        return $this;
    }

    /**
     * Get mainService3
     *
     * @return string
     */
    public function getMainService3() {
        return $this->mainService3;
    }

    /**
     * Set mainService4
     *
     * @param string $mainService4
     *
     * @return CustomWriting
     */
    public function setMainService4($mainService4) {
        $this->mainService4 = $mainService4;

        return $this;
    }

    /**
     * Get mainService4
     *
     * @return string
     */
    public function getMainService4() {
        return $this->mainService4;
    }

    /**
     * Set mainService5
     *
     * @param string $mainService5
     *
     * @return CustomWriting
     */
    public function setMainService5($mainService5) {
        $this->mainService5 = $mainService5;

        return $this;
    }

    /**
     * Get mainService5
     *
     * @return string
     */
    public function getMainService5() {
        return $this->mainService5;
    }

    /**
     * Set mainProvider
     *
     * @param string $mainProvider
     *
     * @return CustomWriting
     */
    public function setMainProvider1($mainProvider) {
        $this->mainProvider1 = $mainProvider;

        return $this;
    }

    /**
     * Get mainProvider
     *
     * @return string
     */
    public function getMainProvider1() {
        return $this->mainProvider1;
    }

    /**
     * Set mainProvider2
     *
     * @param string $mainProvider2
     *
     * @return CustomWriting
     */
    public function setMainProvider2($mainProvider2) {
        $this->mainProvider2 = $mainProvider2;

        return $this;
    }

    /**
     * Get mainProvider2
     *
     * @return string
     */
    public function getMainProvider2() {
        return $this->mainProvider2;
    }

    /**
     * Set mainProvider3
     *
     * @param string $mainProvider3
     *
     * @return CustomWriting
     */
    public function setMainProvider3($mainProvider3) {
        $this->mainProvider3 = $mainProvider3;

        return $this;
    }

    /**
     * Get mainProvider3
     *
     * @return string
     */
    public function getMainProvider3() {
        return $this->mainProvider3;
    }

    /**
     * Set mainPartner
     *
     * @param string $mainPartner
     *
     * @return CustomWriting
     */
    public function setMainPartner1($mainPartner) {
        $this->mainPartner1 = $mainPartner;

        return $this;
    }

    /**
     * Get mainPartner
     *
     * @return string
     */
    public function getMainPartner1() {
        return $this->mainPartner1;
    }

    /**
     * Set mainPartner2
     *
     * @param string $mainPartner2
     *
     * @return CustomWriting
     */
    public function setMainPartner2($mainPartner2) {
        $this->mainPartner2 = $mainPartner2;

        return $this;
    }

    /**
     * Get mainPartner2
     *
     * @return string
     */
    public function getMainPartner2() {
        return $this->mainPartner2;
    }

    /**
     * Set mainPartner3
     *
     * @param string $mainPartner3
     *
     * @return CustomWriting
     */
    public function setMainPartner3($mainPartner3) {
        $this->mainPartner3 = $mainPartner3;

        return $this;
    }

    /**
     * Get mainPartner3
     *
     * @return string
     */
    public function getMainPartner3() {
        return $this->mainPartner3;
    }

    /**
     * Set mainTarget1
     *
     * @param string $mainTarget1
     *
     * @return CustomWriting
     */
    public function setMainTarget1($mainTarget1) {
        $this->mainTarget1 = $mainTarget1;

        return $this;
    }

    /**
     * Get mainTarget1
     *
     * @return string
     */
    public function getMainTarget1() {
        return $this->mainTarget1;
    }

    /**
     * Set mainTarget2
     *
     * @param string $mainTarget2
     *
     * @return CustomWriting
     */
    public function setMainTarget2($mainTarget2) {
        $this->mainTarget2 = $mainTarget2;

        return $this;
    }

    /**
     * Get mainTarget2
     *
     * @return string
     */
    public function getMainTarget2() {
        return $this->mainTarget2;
    }

    /**
     * Set mainTarget3
     *
     * @param string $mainTarget3
     *
     * @return CustomWriting
     */
    public function setMainTarget3($mainTarget3) {
        $this->mainTarget3 = $mainTarget3;

        return $this;
    }

    /**
     * Get mainTarget3
     *
     * @return string
     */
    public function getMainTarget3() {
        return $this->mainTarget3;
    }

    /**
     * Set mainChannels
     *
     * @param array $mainChannels
     *
     * @return CustomWriting
     */
    public function setMainChannels($mainChannels) {
        $this->mainChannels = $mainChannels;

        return $this;
    }

    /**
     * Get mainChannels
     *
     * @return array
     */
    public function getMainChannels() {
        return $this->mainChannels;
    }

    /**
     * Set marketStrategies
     *
     * @param array $marketStrategies
     *
     * @return CustomWriting
     */
    public function setMarketStrategies($marketStrategies) {
        $this->marketStrategies = $marketStrategies;

        return $this;
    }

    /**
     * Get marketStrategies
     *
     * @return array
     */
    public function getMarketStrategies() {
        return $this->marketStrategies;
    }

    /**
     * Set communicationChannels
     *
     * @param array $communicationChannels
     *
     * @return CustomWriting
     */
    public function setCommunicationChannels($communicationChannels) {
        $this->communicationChannels = $communicationChannels;

        return $this;
    }

    /**
     * Get communicationChannels
     *
     * @return array
     */
    public function getCommunicationChannels() {
        return $this->communicationChannels;
    }

    public function getMainIntermediary1() {
        return $this->mainIntermediary1;
    }

    public function getMainIntermediary2() {
        return $this->mainIntermediary2;
    }

    public function getMainIntermediary3() {
        return $this->mainIntermediary3;
    }

    public function setMainIntermediary1($mainIntermediary1) {
        $this->mainIntermediary1 = $mainIntermediary1;
    }

    public function setMainIntermediary2($mainIntermediary2) {
        $this->mainIntermediary2 = $mainIntermediary2;
    }

    public function setMainIntermediary3($mainIntermediary3) {
        $this->mainIntermediary3 = $mainIntermediary3;
    }

    public function getBusinessPlan() {
        return $this->businessPlan;
    }

    public function setBusinessPlan($businessPlan) {
        $this->businessPlan = $businessPlan;
    }

    public function getCvFileName() {
        return $this->cvFileName;
    }

    public function setCvFileName($cvFileName) {
        $this->cvFileName = $cvFileName;
    }

}
