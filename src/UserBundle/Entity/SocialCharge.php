<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SocialCharge
 *
 * @ORM\Table(name="user_social_charge")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\SocialChargeRepository")
 */
class SocialCharge
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="chargeSalariale", type="float")
     */
    private $chargeSalariale;

    /**
     * @var float
     *
     * @ORM\Column(name="chargePatronale", type="float")
     */
    private $chargePatronale;

    /**
     * @var float
     *
     * @ORM\Column(name="cice", type="float")
     */
    private $cice;


    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Country", inversedBy="socialCharges", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $country;


    /**
     * @var Status
     * @ORM\ManyToOne(targetEntity="BPBundle\Entity\Status", inversedBy="chargesSociales", cascade={"persist"})
     */
    protected $status;




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set chargeSalariale
     *
     * @param float $chargeSalariale
     *
     * @return SocialCharge
     */
    public function setChargeSalariale($chargeSalariale)
    {
        $this->chargeSalariale = $chargeSalariale;

        return $this;
    }

    /**
     * Get chargeSalariale
     *
     * @return float
     */
    public function getChargeSalariale()
    {
        return $this->chargeSalariale;
    }

    /**
     * Set chargePatronale
     *
     * @param float $chargePatronale
     *
     * @return SocialCharge
     */
    public function setChargePatronale($chargePatronale)
    {
        $this->chargePatronale = $chargePatronale;

        return $this;
    }

    /**
     * Get chargePatronale
     *
     * @return float
     */
    public function getChargePatronale()
    {
        return $this->chargePatronale;
    }

    /**
     * Set cice
     *
     * @param float $cice
     *
     * @return SocialCharge
     */
    public function setCice($cice)
    {
        $this->cice = $cice;

        return $this;
    }

    /**
     * Get cice
     *
     * @return float
     */
    public function getCice()
    {
        return $this->cice;
    }

    /**
     * Set country
     *
     * @param \BPBundle\Entity\Country $country
     *
     * @return SocialCharge
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
     * Set status
     *
     * @param \BPBundle\Entity\Status $status
     *
     * @return SocialCharge
     */
    public function setStatus(\BPBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BPBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
