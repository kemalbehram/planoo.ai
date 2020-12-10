<?php

namespace BPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rate
 *
 * @ORM\Table(name="bp_rate")
 * @ORM\Entity(repositoryClass="BPBundle\Repository\RateRepository")
 */
class Rate
{
    public function __construct()
    {
        $this->setBaseMin = 0;
        $this->setBaseMax= 0;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    protected $value;


    /**
     * @var string
     *
     * @ORM\Column(name="base", type="string", length=255)
     */
    protected $base;


    /**
     * @var string
     *
     * @ORM\Column(name="base_min", type="float")
     */
    protected $baseMin;

    /**
     * @var string
     *
     * @ORM\Column(name="base_max", type="float", nullable=true)
     */
    protected $baseMax;

    /**
     * @var string
     *
     * @ORM\Column(name="assiette_ca", type="float", nullable=true)
     */
    protected $assietteCa;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\RateLabel", inversedBy="rates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $type;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Country", inversedBy="rates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $country;


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
     * Set type
     *
     * @param string $type
     *
     * @return Rate
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return Rate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set base
     *
     * @param string $base
     *
     * @return Rate
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set baseMin
     *
     * @param string $baseMin
     *
     * @return Rate
     */
    public function setBaseMin($baseMin)
    {
        $this->baseMin = $baseMin;

        return $this;
    }

    /**
     * Get baseMin
     *
     * @return string
     */
    public function getBaseMin()
    {
        return $this->baseMin;
    }

    /**
     * Set baseMax
     *
     * @param string $baseMax
     *
     * @return Rate
     */
    public function setBaseMax($baseMax = null)
    {
        $this->baseMax = $baseMax;

        return $this;
    }

    /**
     * Get baseMax
     *
     * @return string
     */
    public function getBaseMax()
    {
        return $this->baseMax;
    }

    /**
     * Set country
     *
     * @param \BPBundle\Entity\Country $country
     *
     * @return Rate
     */
    public function setCountry(\BPBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \BPBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getAssietteCa()
    {
        return $this->assietteCa;
    }

    /**
     * @param string $assietteCa
     */
    public function setAssietteCa($assietteCa)
    {
        $this->assietteCa = $assietteCa;

        return $this;
    }
}
